<?php

/**
 * Setting
 *
 * @package     ScholarshipOwl\Data\Entity\Website
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	23. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Website;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class Setting extends AbstractEntity {
	const TYPE_INT = "int";
	const TYPE_DECIMAL = "decimal";
	const TYPE_STRING = "string";
	const TYPE_TEXT = "text";
	const TYPE_SELECT = "select";
	const TYPE_ARRAY = "array";
	
	const VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_ALL = "show_all";
	const VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_FREE = "show_free";
	const VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_NONE = "show_none";
	
	const VALUE_SCHOLARSHIPS_VISIBILITY_PRETICK_ALL = "pretick_all";
	const VALUE_SCHOLARSHIPS_VISIBILITY_PRETICK_NONE = "pretick_none";
	
	
	private $settingId;
	private $name;
	private $title;
	private $value;
	private $defaultValue;
	private $options;
	private $type;
	private $group;
    private $isAvailableInRest = 0;
	
	
	public function __construct() {
		$this->settingId = 0;
		$this->name = "";
		$this->title = "";
		$this->value = "";
		$this->defaultValue = "";
		$this->options = array();
		$this->type = "";
		$this->group = "";	
	}
	
	public function getSettingId(){
		return $this->settingId;
	}
	
	public function setSettingId($settingId){
		$this->settingId = $settingId;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setName($name){
		$this->name = $name;
	}
	
	public function getTitle(){
		return $this->title;
	}
	
	public function setTitle($title){
		$this->title = $title;
	}
	
	public function getValue(){
		return $this->value;
	}
	
	public function setValue($value){
		$this->value = $value;
	}
	
	public function getDefaultValue(){
		return $this->defaultValue;
	}
	
	public function setDefaultValue($defaultValue){
		$this->defaultValue = $defaultValue;
	}
	
	public function getOptions(){
		return $this->options;
	}
	
	public function setOptions($options){
		$this->options = $options;
	}
	
	public function getType(){
		return $this->type;
	}
	
	public function setType($type){
		$this->type = $type;
	}
	
	public function getGroup(){
		return $this->group;
	}
	
	public function setGroup($group){
		$this->group = $group;
	}
	
	public function isInt() {
		return $this->type == self::TYPE_INT;
	}
	
	public function isDecimal() {
		return $this->type == self::TYPE_DECIMAL;
	}
	
	public function isString() {
		return $this->type == self::TYPE_STRING;
	}
	
	public function isText() {
		return $this->type == self::TYPE_TEXT;
	}
	
	public function isSelect() {
		return $this->type == self::TYPE_SELECT;
	}
	
	public function isArray() {
		return $this->type == self::TYPE_ARRAY;
	}

    /**
     * @return integer
     */
    public function getIsAvailableInRest()
    {
        return $this->isAvailableInRest;
    }

    /**
     * @param integer $isAvailableInRest
     */
    public function setIsAvailableInRest($isAvailableInRest)
    {
        $this->isAvailableInRest = $isAvailableInRest;
    }

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "setting_id") {
				$this->setSettingId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
			else if($key == "title") {
				$this->setTitle($value);
			}
			else if($key == "value") {
				$this->setValue($value);
			}
			else if($key == "default_value") {
				$this->setDefaultValue($value);
			}
			else if($key == "options") {
				$this->setOptions($value);
			}
			else if($key == "type") {
				$this->setType($value);
			}
			else if($key == "group") {
				$this->setGroup($value);
			}
			else if($key == "is_available_in_rest") {
				$this->setIsAvailableInRest($value);
			}
		}
	}
	
	public function toArray() {
		return array(
			"setting_id" => $this->getSettingId(),
			"name" => $this->getName(),
			"title" => $this->getTitle(),
			"value" => $this->getValue(),
			"default_value" => $this->getDefaultValue(),
			"options" => $this->getOptions(),
			"type" => $this->getType(),
			"group" => $this->getGroup(),
		);
	}
}
