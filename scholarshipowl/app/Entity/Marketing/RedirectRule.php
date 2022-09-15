<?php

namespace App\Entity\Marketing;

use Doctrine\ORM\Mapping as ORM;

/**
 * RedirectRule
 *
 * @ORM\Table(name="redirect_rule")
 * @ORM\Entity
 */
class RedirectRule {
    const OPERATOR_EQUAL = "=";
    const OPERATOR_NOT_EQUAL = "!=";
    const OPERATOR_GREATER = ">";
    const OPERATOR_GREATER_EQUAL = ">=";
    const OPERATOR_LESS = "<";
    const OPERATOR_LESS_EQUAL = "<=";
    const OPERATOR_LIKE = "LIKE";
    const OPERATOR_IN = "IN";

    /**
     * @var integer
     *
     * @ORM\Column(name="redirect_rule_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $redirectRuleId;

    /**
     * @var RedirectRulesSet
     *
     * @ORM\ManyToOne(targetEntity="RedirectRulesSet")
     * @ORM\JoinColumn(name="redirect_rules_set_id", referencedColumnName="redirect_rules_set_id")
     */
    private $redirectRulesSet;

    /**
     * @var string
     *
     * @ORM\Column(name="field", type="string", length=255, nullable=true)
     */
    private $field;

    /**
     * @var string
     *
     * @ORM\Column(name="operator", type="string", length=10, nullable=true)
     */
    private $operator;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=true)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * RedirectRule constructor.
     *
     * @param      $field
     * @param      $operator
     * @param      $value
     * @param bool $active
     */
    public function __construct($field, $operator, $value, $active = true)
    {
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->setActive($active);
    }

    /**
     * @return int
     */
    public function getRedirectRuleId(): int
    {
        return $this->redirectRuleId;
    }

    /**
     * @param int $redirectRuleId
     */
    public function setRedirectRuleId(int $redirectRuleId)
    {
        $this->redirectRuleId = $redirectRuleId;
    }

    /**
     * @return RedirectRulesSet
     */
    public function getRedirectRulesSet(): RedirectRulesSet
    {
        return $this->redirectRulesSet;
    }

    /**
     * @param RedirectRulesSet $redirectRulesSet
     */
    public function setRedirectRulesSet(RedirectRulesSet $redirectRulesSet)
    {
        $this->redirectRulesSet = $redirectRulesSet;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @param string $field
     */
    public function setField(string $field)
    {
        $this->field = $field;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator(string $operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getActive(): string
    {
        return $this->active;
    }

    /**
     * @param string $active
     */
    public function setActive(string $active)
    {
        $this->active = $active;
    }

    /**
     * @return array
     */
    public static function getRedirectRuleOperatorTypes() {
        return array(
            self::OPERATOR_EQUAL => "Equal",
            self::OPERATOR_NOT_EQUAL => "Not equal",
            self::OPERATOR_GREATER => "Greater then",
            self::OPERATOR_GREATER_EQUAL => "Greater then or equal",
            self::OPERATOR_LESS => "Less then",
            self::OPERATOR_LESS_EQUAL => "Less then or equal",
            self::OPERATOR_LIKE => "Like",
            self::OPERATOR_IN => "In",
        );
    }
}
