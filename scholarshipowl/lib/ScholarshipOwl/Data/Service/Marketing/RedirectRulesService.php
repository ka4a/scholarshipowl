<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 1/12/2015
 */

namespace ScholarshipOwl\Data\Service\Marketing;


use App\Entity\College;
use App\Entity\Profile;
use ScholarshipOwl\Data\Entity\Marketing\RedirectRule;
use ScholarshipOwl\Data\Entity\Marketing\RedirectRulesSet;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Service\Account\ProfileService;

class RedirectRulesService extends AbstractService implements IRedirectRulesService {
	private $customFields = array(
		"Age" => "Age",
        'doe' => 'Doe code'
	);

	public function getRedirectRulesSet($redirectRulesSetId, $rules = true, $onlyActive = false){
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE redirect_rules_set_id = ?", self::TABLE_REDIRECT_RULES_SET);
		$resultSet = $this->query($sql, array($redirectRulesSetId));

		foreach ($resultSet as $row) {
			$result = new RedirectRulesSet();
			$result->populate($row);
		}

		if (isset($result) && $rules == true) {
			$sql = sprintf("SELECT * FROM %s WHERE redirect_rules_set_id = ?".(($onlyActive)?" AND active = 1":""), self::TABLE_REDIRECT_RULE);
			$resultSet = $this->query($sql, array($result->getRedirectRulesSetId()));
			foreach ($resultSet as $row) {
				$entity = new RedirectRule();
				$entity->populate((array) $row);

				$result->addRedirectRule($entity);
			}
		}

		return $result;
	}

	public function getRedirectRulesSets(){
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_REDIRECT_RULES_SET);
		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new RedirectRulesSet();
			$entity->populate((array) $row);

			$result[$entity->getRedirectRulesSetId()] = $entity;
		}

		$redirectRulesSetsIds = array_keys($result);
		if (!empty($affiliateIds)) {
			$sql = sprintf("SELECT * FROM %s WHERE redirect_rules_set_id IN(%s)", self::TABLE_REDIRECT_RULE, implode(",", $redirectRulesSetsIds));
			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$entity = new RedirectRule();
				$entity->populate((array) $row);

				$result[$entity->getRedirectRulesSetId()]->addRedirectRule($entity);
			}
		}

		return $result;
	}

	public function addRedirectRulesSet(RedirectRulesSet $redirectRulesSet){
		return $this->saveRedirectRulesSet($redirectRulesSet);
	}

	public function updateRedirectRulesSet(RedirectRulesSet $redirectRulesSet){
		return $this->saveRedirectRulesSet($redirectRulesSet, false);
	}

	public function saveRedirectRulesSet(RedirectRulesSet $redirectRulesSet, $insert = true){
		$result = 0;

		$redirectRulesSetId = $redirectRulesSet->getRedirectRulesSetId();
		$data = $redirectRulesSet->toArray();

		unset($data["redirect_rules_set_id"]);
		try {
			$this->beginTransaction();
			if($insert == true) {
				$this->insert(self::TABLE_REDIRECT_RULES_SET, $data);
				$redirectRulesSetId = $this->getLastInsertId();

				$result = $redirectRulesSetId;
			}
			else {
				$result = $this->update(self::TABLE_REDIRECT_RULES_SET, $data, array("redirect_rules_set_id" => $redirectRulesSetId));
			}

			$redirectRules = $redirectRulesSet->getRedirectRules();

			if (!empty($redirectRules)) {
				foreach($redirectRulesSet->getRedirectRules() as $redirectRule) {
					$redirectRuleId = $redirectRule->getRedirectRuleId();
					$data = $redirectRule->toArray();

					unset($data["redirect_rule_id"]);

					$this->insert(self::TABLE_REDIRECT_RULE, $data);
				}
			}

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}

	public function deleteRedirectRulesSet($redirectRulesSetId){
		$sql = sprintf("SELECT COUNT(*) as cnt FROM %s WHERE redirect_rules_set_id = ?", self::TABLE_AFFILIATE_GOAL_MAPPING);
		$result = $this->query($sql, array($redirectRulesSetId));

		if($result[0]->cnt > 0){
			return false;
		}
		$this->execute(sprintf("DELETE FROM %s WHERE redirect_rules_set_id = ?", self::TABLE_REDIRECT_RULE), array($redirectRulesSetId));
		return $this->execute(sprintf("DELETE FROM %s WHERE redirect_rules_set_id = ?", self::TABLE_REDIRECT_RULES_SET), array($redirectRulesSetId));
	}

	public function getCustomFields(){
		return $this->customFields;
	}

	public function checkUserAgainstRules($redirectRulesSetId, $accountId){
		$redirectRuleSet = $this->getRedirectRulesSet($redirectRulesSetId, true, true);
		$lastOperator = "AND (";

		if ($redirectRuleSet instanceof RedirectRulesSet && count($redirectRuleSet->getRedirectRules())) {
			$sql = sprintf("SELECT COUNT(*) AS count FROM %s ", $redirectRuleSet->getTable());

			$where = "WHERE account_id = ".$accountId." AND (";
			$redirectRules = $redirectRuleSet->getRedirectRules();
			$hasRules = false;

			if(count($redirectRules)) {
				foreach ($redirectRules as $redirectRule) {
					if (($redirectRule->getField() && $redirectRule->getField() != "--- Select ---") && $redirectRule->getOperator() && $redirectRule->getValue() ) {
						if (array_key_exists($redirectRule->getField(), $this->customFields) && $accountId != null) {
                            /**
                             * @var Profile $profile
                             */
                            $profile = \EntityManager::getRepository(Profile::class)->find($accountId);
							if ($redirectRule->getField() == "Age") {
								if($profile && $profile->getAge()) {
									$where .= $profile->getAge() . " " . $redirectRule->getOperator() . " '" . $redirectRule->getValue() . "' " . $redirectRuleSet->getType() . " ";
									$hasRules = true;
								}
							} else if($redirectRule->getField() == "doe") {
							    $universities = $profile->getUniversities();

                                /**
                                 * @var College[] $profileUniversities
                                 */
                                $profileUniversities = \EntityManager::getRepository(College::class)->findBy(['canonicalName' => $universities]);

                                if (!is_null($profileUniversities) && is_array($profileUniversities)) {
                                    $ipedList = explode(',', str_replace("\n", "", $redirectRule->getValue()));
                                    $wherePart = ' 0 AND ';

                                    foreach ($profileUniversities as $university) {
                                        $colId = rtrim($university->getDoeCode(), "0");
                                        if (in_array($colId, $ipedList) || in_array($university->getDoeCode(), $ipedList) ) {
                                            $hasRules = true;
                                            $wherePart = ' 1 AND ';
                                        }
                                    }
                                    $where .= $wherePart;

                                }

                            }
						} else {
							$where .= $redirectRule->getField() . " " . $redirectRule->getOperator() . (($redirectRule->getOperator() == "IN")?" (".$redirectRule->getValue().") ":" '" . $redirectRule->getValue() . "' ") . $redirectRuleSet->getType() . " ";
							$hasRules = true;
						}

						$lastOperator = $redirectRuleSet->getType();
					}
				}

				$sql .= substr($where, 0, -(1 + strlen($lastOperator))) . ")";

				if($hasRules) {
					$resultSet = $this->query($sql);

					if ($resultSet[0]->count > 0) {
						return true;
					}
				}
			}else{
				return true;
			}
		}
		return false;
	}

	public function getRedirectRule($redirectRuleId){
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE redirect_rule_id = ?", self::TABLE_REDIRECT_RULE);
		$resultSet = $this->query($sql, array($redirectRuleId));

		foreach ($resultSet as $row) {
			$result = new RedirectRule();
			$result->populate($row);
		}

		return $result;
	}

	public function getRedirectRulesInSet($redirectRulesSetId){
		$result = array();

		$sql = sprintf("SELECT * FROM %s WHERE redirect_rules_set_id = ?", self::TABLE_REDIRECT_RULE);
		$resultSet = $this->query($sql, array($redirectRulesSetId));
		foreach ($resultSet as $row) {
			$entity = new RedirectRule();
			$entity->populate((array) $row);

			$result[] = $entity;
		}

		return $result;
	}

	public function getRedirectRules(){
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_REDIRECT_RULE);
		$resultSet = $this->query($sql);
		foreach ($resultSet as $row) {
			$entity = new RedirectRule();
			$entity->populate((array) $row);

			$result[] = $entity;
		}

		return $result;
	}

	public function addRedirectRule(RedirectRule $redirectRule){
		return $this->saveRedirectRule($redirectRule);
	}

	public function updateRedirectRule(RedirectRule $redirectRule){
		return $this->saveRedirectRule($redirectRule, false);
	}

	public function saveRedirectRule(RedirectRule $redirectRule, $insert = true){
		$result = 0;

		$redirectRuleId = $redirectRule->getRedirectRuleId();
		$data = $redirectRule->toArray();

		unset($data["redirect_rule_id"]);

		$this->beginTransaction();
		if($insert == true) {
			$this->insert(self::TABLE_REDIRECT_RULE, $data);
			$redirectRuleId = $this->getLastInsertId();

			$result = $redirectRuleId;
		}
		else {
			$result = $this->update(self::TABLE_REDIRECT_RULE, $data, array("redirect_rules_set_id" => $redirectRuleId));
		}

		return $result;
	}

	public function deleteRedirectRule($redirectRuleId){
		return $this->execute(sprintf("DELETE FROM %s WHERE redirect_rule_id = ?", self::TABLE_REDIRECT_RULE), array($redirectRuleId));
	}
}