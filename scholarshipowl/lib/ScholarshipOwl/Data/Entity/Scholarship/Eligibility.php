<?php

/**
 * Eligibility
 *
 * @package     ScholarshipOwl\Data\Entity\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	01. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Scholarship;

use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Info\Field;


class Eligibility extends AbstractEntity {

    const TYPE_REQUIRED = 'required';
    const TYPE_VALUE = 'value';
    const TYPE_LESS_THAN = 'less_than';
    const TYPE_LESS_THAN_OR_EQUAL = 'less_than_or_equal';
    const TYPE_GREATER_THAN = 'greater_than';
    const TYPE_GREATER_THAN_OR_EQUAL = 'greater_than_or_equal';
    const TYPE_NOT = 'not';
    const TYPE_IN = 'in';
    const TYPE_NIN = 'nin';
    const TYPE_BETWEEN = 'between';

	private $eligibilityId;
	private $scholarship;
	private $field;
	private $type;
	private $value;
	private $isOptional;


	public function __construct() {
		$this->eligibilityId = 0;
		$this->scholarship = new Scholarship();
		$this->field = new Field();
		$this->type = "";
		$this->value = "";
		$this->is_optional = 0;
	}

	public function getEligibilityId(){
		return $this->eligibilityId;
	}

	public function setEligibilityId($eligibilityId){
		$this->eligibilityId = $eligibilityId;
	}

	public function getScholarship(){
		return $this->scholarship;
	}

	public function setScholarship(Scholarship $scholarship){
		$this->scholarship = $scholarship;
	}

	public function getField(){
		return $this->field;
	}

	public function setField(Field $field){
		$this->field = $field;
	}

	public function getType(){
		return $this->type;
	}

	public function setType($type){
		$this->type = $type;
	}

	public function getValue(){
		return $this->value;
	}

	public function setValue($value){
		$this->value = $value;
	}

    public function setIsOptional($val)
    {
        return $this->isOptional = $val;
    }

    public function getIsOptional()
    {
        return $this->isOptional;
    }

	public static function getTypes() {
		return [
            \App\Entity\Eligibility::TYPE_REQUIRED => 'Required',
            \App\Entity\Eligibility::TYPE_VALUE => 'Value',
            \App\Entity\Eligibility::TYPE_LESS_THAN => 'Less than',
            \App\Entity\Eligibility::TYPE_LESS_THAN_OR_EQUAL => 'Less than or equal',
            \App\Entity\Eligibility::TYPE_GREATER_THAN => 'Greater than',
            \App\Entity\Eligibility::TYPE_GREATER_THAN_OR_EQUAL => 'Greater than or equal',
            \App\Entity\Eligibility::TYPE_NOT => 'Not',
            \App\Entity\Eligibility::TYPE_IN => 'In',
            \App\Entity\Eligibility::TYPE_NIN => 'Not in',
            \App\Entity\Eligibility::TYPE_BETWEEN => 'Between',
            \App\Entity\Eligibility::TYPE_BOOL => 'Boolean (Yes/No)'
		];
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "eligibility_id") {
				$this->setEligibilityId($value);
			}
			else if($key == "scholarship_id") {
				$this->getScholarship()->setScholarshipId($value);
			}
			else if($key == "field_id") {
				$this->getField()->setFieldId($value);
			}
            else if($key == 'field_name') {
                $this->getField()->setName($value);
            }
			else if($key == "type") {
				$this->setType($value);
			}
			else if($key == "value") {
				$this->setValue($value);
			} else if ($key === 'is_optional') {
			    $this->setIsOptional($value);
			}
		}
	}

	public function toArray() {
		return array(
			"eligibility_id" => $this->getEligibilityId(),
			"scholarship_id" => $this->getScholarship()->getScholarshipId(),
			"field_id" => $this->getField()->getFieldId(),
			"type" => $this->getType(),
			"value" => $this->getValue(),
			"is_optional" => $this->getIsOptional()
 		);
	}
}
