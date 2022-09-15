<?php

/**
 * Redirect Rule Set Class
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


class RedirectRulesSet extends AbstractEntity {
	const TYPE_ALL = "AND";
	const TYPE_ANY = "OR";

	private $redirectRulesSetId;
	private $name;
	private $type;
	private $table;

	private $redirectRules;


	public function __construct() {
		$this->redirectRulesSetId = 0;
		$this->name = "";
		$this->type = "AND";
		$this->table = "";

		$this->redirectRules = array();
	}

	public function getRedirectRulesSetId(){
		return $this->redirectRulesSetId;
	}

	public function setRedirectRulesSetId($redirectRulesSetId){
		$this->redirectRulesSetId = $redirectRulesSetId;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getTable(){
		return $this->table;
	}

	public function setTable($table){
		$this->table = $table;
	}


	public function addRedirectRule(RedirectRule $redirectRule) {
		if($redirectRule->getRedirectRuleId() != 0){
			$this->redirectRules[$redirectRule->getRedirectRuleId()] = $redirectRule;
		}else{
			$this->redirectRules[] = $redirectRule;
		}

	}

	public function getRedirectRules() {
		return $this->redirectRules;
	}

	public function setRedirectRules($redirectRules) {
		foreach ($redirectRules as $redirectRule) {
			$this->addRedirectRule($redirectRule);
		}
	}

	public static function getRedirectRulesSetTypes() {
		return array(
			self::TYPE_ALL => "All",
			self::TYPE_ANY => "Any"
		);
	}

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "redirect_rules_set_id") {
				$this->setRedirectRulesSetId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
			else if ($key == "type") {
				$this->setType($value);
			}
			else if ($key == "table_name") {
				$this->setTable($value);
			}
		}
	}

	public function toArray() {
		return array(
			"redirect_rules_set_id" => $this->getRedirectRulesSetId(),
			"name" => $this->getName(),
			"type" => $this->getType(),
			"table_name" => $this->getTable()
		);
	}
}
