<?php

namespace App\Services\Marketing;


use App\Entity\Marketing\Coreg\CoregRequirementsRule;
use App\Entity\Marketing\Coreg\CoregRequirementsRuleSet;
use App\Entity\Marketing\CoregPlugin;
use Doctrine\ORM\EntityManager;

class CoregRequirementsRuleService
{

    const CUSTOM_FIELDS = ["Age" => "Age"];

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * CoregRequirementsRuleService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Check single redirect rule to user
     *
     * @param int $requirementsRuleSetId
     * @param      $accountId
     * @param bool $isSend
     *
     * @return bool
     */
    public function checkUserAgainstRules(int $requirementsRuleSetId, $accountId, $isSend = false)
    {
        /** @var CoregRequirementsRuleSet $requirementsRuleSet */
        $requirementsRuleSet = $this->em->getRepository(CoregRequirementsRuleSet::class)->find($requirementsRuleSetId);

        if ($requirementsRuleSet) {
            $sql = sprintf("SELECT COUNT(*) AS count FROM %s ", $requirementsRuleSet->getTableName());

            $where[] = "WHERE account_id = " . $accountId;
            $coregRequirementsRules = $requirementsRuleSet->getCoregRequirementsRule();
            $hasRules = false;

            if (count($coregRequirementsRules)) {
                $ruleWhere = [];
                $ruleType = $requirementsRuleSet->getType();
                /** @var CoregRequirementsRule $requirementsRule */
                foreach ($coregRequirementsRules as $requirementsRule) {
                    if ($isSend || $requirementsRule->getIsShowRule()) {
                        $ruleWhere = $this->generateWhereForRule($requirementsRule, $ruleWhere);
                        $hasRules = true;
                    }
                }

                $sql .= implode(' ' . $ruleType . ' ', array_merge($where, $ruleWhere));
                if ($hasRules) {
                    $resultSet = \DB::select($sql);

                    if ($resultSet[0]->count > 0) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Check bulk redirect rule from coregs
     *
     * @param $coregs
     * @param $accountId
     *
     * @return array
     */
    public function checkUserAgainstAllRuleList($coregs, $accountId)
    {
        $selectQueryArray = [];
        $where[] = "WHERE account_id = " . $accountId;
        /**
         * @var CoregPlugin $coreg
         */
        foreach ($coregs as $key => $coreg) {

            /** @var CoregRequirementsRuleSet $requirementsRuleSet */
            //use only first element now
            $requirementsRuleSet = $coreg->getCoregRequirementsRuleSet()[0];
            if ($requirementsRuleSet) {
                $sql = sprintf(
                    "SELECT COUNT(*) AS count, (select @g :=$key) as rr_id FROM %s ",
                    $requirementsRuleSet->getTableName()
                );

                $coregRequirementsRules = $requirementsRuleSet->getCoregRequirementsRule();
                $hasRules = false;

                if (!is_null($coregRequirementsRules) && count($coregRequirementsRules)) {
                    $ruleWhere = [];
                    $ruleType = $requirementsRuleSet->getType();
                    /** @var CoregRequirementsRule $requirementsRule */
                    foreach ($coregRequirementsRules as $requirementsRule) {
                        if ($requirementsRule->getIsShowRule()) {
                            $ruleWhere = $this->generateWhereForRule($requirementsRule, $ruleWhere);
                            $hasRules = true;
                        }
                    }

                    $sql .= implode(' ' . $ruleType . ' ', array_merge($where, $ruleWhere));

                    if ($hasRules) {
                        $selectQueryArray[$key] = $sql;
                    }
                }
            }
        }

        $result = [];

        if (!empty($selectQueryArray)) {
            $unionQuery = implode(' UNION ', $selectQueryArray);
            $resultSet = \DB::select($unionQuery);
            foreach ($resultSet as $res) {
                if ($res->count == 1) {
                    $result[] = $coregs[$res->rr_id];
                }
            }
        }

        return $result;
    }

    /**
     * Return table field for custom rule
     *
     * @param CoregRequirementsRule $requirementsRule
     *
     * @return string
     */
    protected function getFieldForCustomRule(CoregRequirementsRule $requirementsRule)
    {
        $field = '';
        $customField = strtolower($requirementsRule->getField());
        $customRuleFields = [
            'age' => '(TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()))'
        ];

        if (array_key_exists($customField, $customRuleFields)) {
            $field = $customRuleFields[$customField];
        }

        return $field;
    }

    /**
     * @param $requirementsRule
     * @param $ruleWhere
     *
     * @return array
     */
    protected function generateWhereForRule($requirementsRule, $ruleWhere)
    {
        $field = $requirementsRule->getField();

        if (array_key_exists($requirementsRule->getField(), self::CUSTOM_FIELDS)) {
            $field = $this->getFieldForCustomRule($requirementsRule);
        }

        $operator = $requirementsRule->getOperator() == CoregRequirementsRule::OPERATOR_SET ? '!=' : $requirementsRule->getOperator();

        $valueFormat = ($operator == "IN") ? '( %s )' : " '%s' ";
        $ruleValue = ($operator == "IN") ? $this->prepareRulesValueWithQuotes($requirementsRule->getValue()) : $requirementsRule->getValue();

        $value = sprintf($valueFormat, $ruleValue);
        $ruleWhere[] = sprintf('%s %s %s ', $field, $operator, $value);

        return $ruleWhere;
    }

    protected function prepareRulesValueWithQuotes($value)
    {
        return "'" . implode("', '", array_map('trim', explode(',', $value))) . "'";
    }
}
