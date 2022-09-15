<?php

namespace App\Services\Marketing;


use App\Entity\College;
use App\Entity\Marketing\CoregPlugin;
use App\Entity\Marketing\RedirectRule;
use App\Entity\Marketing\RedirectRulesSet;
use App\Entity\Profile;
use Doctrine\ORM\EntityManager;

class RedirectRulesService
{
    private $customFields = array(
        "Age" => "Age",
        "doe" => "Doe code"
    );
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * RedirectRulesService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(
        EntityManager $em
    ) {
        $this->em = $em;
    }

    public function getCustomFields()
    {
        return $this->customFields;
    }

    /**
     *
     * Check single redirect rule to user
     * @param $redirectRulesSetId
     * @param $accountId
     *
     * @return bool
     */
    public function checkUserAgainstRules($redirectRulesSetId, $accountId)
    {
        /** @var RedirectRulesSet $redirectRuleSet */
        $redirectRuleSet = $this->em->getRepository(RedirectRulesSet::class)->find($redirectRulesSetId);
        $lastOperator = "AND (";

        if ($redirectRuleSet) {
            $sql = sprintf("SELECT COUNT(*) AS count FROM %s ", $redirectRuleSet->getTableName());

            $where = "WHERE account_id = " . $accountId . " AND (";
            $redirectRules = $redirectRuleSet->getRedirectRules();
            $hasRules = false;

            if (count($redirectRules)) {
                /** @var RedirectRule $redirectRule */
                foreach ($redirectRules as $redirectRule) {
                    if(!$redirectRule->getActive()){
                        continue;
                    }

                    if (($redirectRule->getField() && $redirectRule->getField() != "--- Select ---") && $redirectRule->getOperator() && null !== $redirectRule->getValue()) {
                        if (array_key_exists($redirectRule->getField(), $this->customFields) && $accountId != null) {
                            /**
                             * @var Profile $profile
                             */
                            $profile = $this->em->getRepository(Profile::class)->find($accountId);
                            if ($redirectRule->getField() == "Age") {
                                if ($profile && $profile->getAge()) {
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
                            $where .= $redirectRule->getField() . " " . $redirectRule->getOperator() . (($redirectRule->getOperator() == "IN") ? " (" . $redirectRule->getValue() . ") " : " '" . $redirectRule->getValue() . "' ") . $redirectRuleSet->getType() . " ";
                            $hasRules = true;
                        }

                        $lastOperator = $redirectRuleSet->getType();
                    }
                }

                $sql .= substr($where, 0, -(1 + strlen($lastOperator))) . ")";
                if ($hasRules) {
                    $resultSet = \DB::select($sql);

                    if ($resultSet[0]->count > 0) {
                        return true;
                    }
                }
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Check bulk redirect rule from coregs
     * @param $coregs
     * @param $accountId
     *
     * @return array
     */
    public function checkUserAgainstAllRuleList($coregs, $accountId)
    {
        $selectQueryArray = [];
        $lastOperator = "AND (";

        /**
         * @var CoregPlugin $coreg
         */
        foreach ($coregs as $key => $coreg) {
            /** @var RedirectRulesSet $redirectRuleSet */
            $redirectRuleSet = $coreg->getRedirectRulesSet();
            if ($redirectRuleSet) {
                $sql = sprintf(
                    "SELECT COUNT(*) AS count, (select @g :=$key) as rr_id FROM %s ",
                    $redirectRuleSet->getTableName()
                );

                $where = "WHERE account_id = " . $accountId . " AND (";
                $redirectRules = $redirectRuleSet->getRedirectRules();
                $hasRules = false;

                if (count($redirectRules)) {
                    /** @var RedirectRule $redirectRule */
                    foreach ($redirectRules as $redirectRule) {
                        if(!$redirectRule->getActive()){
                            continue;
                        }

                        if (($redirectRule->getField() && $redirectRule->getField() != "--- Select ---")
                            && $redirectRule->getOperator()
                            && null !== $redirectRule->getValue()
                        ) {
                            if (array_key_exists($redirectRule->getField(), $this->customFields)) {
                                if ($redirectRule->getField() == "Age") {
                                    $profile = $this->em->getRepository(
                                        Profile::class
                                    )->find($accountId);
                                    if ($profile && $profile->getAge()) {
                                        $where .= $profile->getAge() . " "
                                            . $redirectRule->getOperator()
                                            . " '" . $redirectRule->getValue()
                                            . "' " . $redirectRuleSet->getType()
                                            . " ";
                                        $hasRules = true;
                                    }
                                }
                            } else {
                                $where .= $redirectRule->getField() . " "
                                    . $redirectRule->getOperator()
                                    . (($redirectRule->getOperator() == "IN")
                                        ? " (" . $redirectRule->getValue()
                                        . ") "
                                        : " '" . $redirectRule->getValue()
                                        . "' ") . $redirectRuleSet->getType()
                                    . " ";
                                $hasRules = true;
                            }

                            $lastOperator = $redirectRuleSet->getType();
                        }
                    }

                    $sql .= substr($where, 0, -(1 + strlen($lastOperator)))
                        . ")";
                    if ($hasRules) {
                        $selectQueryArray[$key] = $sql;
                    }
                }
            }
        }

        $result = [];

        if(!empty($selectQueryArray)){
            $unionQuery =  implode(' UNION ', $selectQueryArray);
            $resultSet = \DB::select($unionQuery);
            foreach ($resultSet as $res){
                if($res->count == 1){
                    $result[] = $coregs[$res->rr_id];
                }
            }
        }

        return $result;
    }
}
