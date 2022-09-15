<?php
namespace App\Entity\Marketing\Coreg;

use App\Entity\Marketing\RedirectRulesSet;
use Doctrine\ORM\Mapping as ORM;

/**
 * CoregRequirementsRule
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="coreg_requirements_rule", indexes={@ORM\Index(name="fk_redirect_rule_redirect_rules_set_idx", columns={"redirect_rules_set_id"})})
 * @ORM\Entity
 */
class CoregRequirementsRule
{
    const OPERATOR_EQUAL = "=";
    const OPERATOR_NOT_EQUAL = "!=";
    const OPERATOR_GREATER = ">";
    const OPERATOR_GREATER_EQUAL = ">=";
    const OPERATOR_LESS = "<";
    const OPERATOR_LESS_EQUAL = "<=";
    const OPERATOR_LIKE = "LIKE";
    const OPERATOR_IN = "IN";
    const OPERATOR_SET = "SET";

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var RedirectRulesSet
     *
     * @ORM\ManyToOne(targetEntity="CoregRequirementsRuleSet")
     * @ORM\JoinColumn(name="coreg_requirements_rule_set_id", referencedColumnName="id")
     */
    private $coregRequirementsRuleSet;

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
     * @var boolean
     *
     * @ORM\Column(name="is_show_rule", type="boolean")
     */
    private $isShowRule;
    /**
     * @var string
     *
     * @ORM\Column(name="is_send_rule", type="boolean")
     */
    private $isSendRule;

    /**
     * RedirectRule constructor.
     *
     * @param      $field
     * @param      $operator
     * @param      $value
     * @param bool $show
     * @param bool $send
     */
    public function __construct($field, $operator, $value, $show = true, $send = false)
    {
        $this->setField($field);
        $this->setOperator($operator);
        $this->setValue($value);
        $this->setIsShowRule($show);
        $this->setIsSendRule($send);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $redirectRuleId
     */
    public function setId(int $id)
    {
        $this->id= $id;
    }

    /**
     * @return RedirectRulesSet
     */
    public function getRedirectRulesSet(): RedirectRulesSet
    {
        return $this->coregRequirementsRuleSet;
    }

    /**
     * @param CoregRequirementsRuleSet
     */
    public function setCoregRequirementsRuleSet(CoregRequirementsRuleSet $coregRequirementsRuleSet)
    {
        $this->coregRequirementsRuleSet = $coregRequirementsRuleSet;
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
     * @return boolean
     */
    public function getIsShowRule()
    {
        return $this->isShowRule;
    }

    /**
     * @param boolean $isShowRule
     */
    public function setIsShowRule(bool $isShowRule)
    {
        $this->isShowRule = $isShowRule;
    }

    /**
     * @return string
     */
    public function getIsSendRule(): string
    {
        return $this->isSendRule;
    }

    /**
     * @param string $isSendRule
     *
     * @return CoregRequirementsRule
     */
    public function setIsSendRule(string $isSendRule)
    {
        $this->isSendRule = $isSendRule;

        return $this;
    }

    /**
     * @return array
     */
    public static function getCoregRequirementsRuleOperators() {
        return array(
            self::OPERATOR_EQUAL => "Equal",
            self::OPERATOR_NOT_EQUAL => "Not equal",
            self::OPERATOR_GREATER => "Greater then",
            self::OPERATOR_GREATER_EQUAL => "Greater then or equal",
            self::OPERATOR_LESS => "Less then",
            self::OPERATOR_LESS_EQUAL => "Less then or equal",
            self::OPERATOR_LIKE => "Like",
            self::OPERATOR_IN => "In",
            self::OPERATOR_SET => "Set",
        );
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostRemove()
     */
    public function flushCacheTag()
    {
        \Cache::tags([CoregRequirementsRuleSet::CACHE_TAG])->flush();
    }
}
