<?php

/**
 * Redirect Rules Class
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	13. November 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class RedirectRule extends AbstractEntity {
	const OPERATOR_EQUAL = "=";
	const OPERATOR_NOT_EQUAL = "!=";
	const OPERATOR_GREATER = ">";
	const OPERATOR_GREATER_EQUAL = ">=";
	const OPERATOR_LESS = "<";
	const OPERATOR_LESS_EQUAL = "<=";
	const OPERATOR_LIKE = "LIKE";
	const OPERATOR_IN = "IN";

	private $redirectRuleId;
	private $redirectRulesSetId;
	private $field;
	private $operator;
	private $value;
	private $active;


	public function __construct() {
		$this->redirectRuleId = 0;
		$this->redirectRulesSetId = 0;
		$this->field = "";
		$this->operator = self::OPERATOR_EQUAL;
		$this->value = "";
		$this->active = false;
	}

	public function getRedirectRuleId(){
		return $this->redirectRuleId;
	}

	public function setRedirectRuleId($redirectRuleId){
		$this->redirectRuleId = $redirectRuleId;
	}

	public function getRedirectRulesSetId(){
		return $this->redirectRulesSetId;
	}

	public function setRedirectRulesSetId($redirectRulesSetId){
		$this->redirectRulesSetId = $redirectRulesSetId;
	}

	public function getField(){
		return $this->field;
	}

	public function setField($field){
		$this->field = $field;
	}

	public function getOperator(){
		return $this->operator;
	}

	public function setOperator($operator){
		$this->operator = $operator;
	}

	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value = $value;
	}

	public function isActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}

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

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "redirect_rule_id") {
				$this->setRedirectRuleId($value);
			}else if ($key == "redirect_rules_set_id") {
				$this->setRedirectRulesSetId($value);
			}
			else if ($key == "field") {
				$this->setField($value);
			}
			else if ($key == "operator") {
				$this->setOperator($value);
			}
			else if ($key == "value") {
				$this->setValue($value);
			}
			else if ($key == "active") {
				$this->setActive($value);
			}
		}
	}

	public function toArray() {
		return array(
			"redirect_rule_id" => $this->getRedirectRuleId(),
			"redirect_rules_set_id" => $this->getRedirectRulesSetId(),
			"field" => $this->getField(),
			"operator" => $this->getOperator(),
			"value" => $this->getValue(),
			"active" => $this->isActive(),
		);
	}
}
