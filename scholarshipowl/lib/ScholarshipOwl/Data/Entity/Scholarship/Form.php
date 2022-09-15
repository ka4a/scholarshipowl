<?php

/**
 * Form
 *
 * @package     ScholarshipOwl\Data\Entity\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	23. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Scholarship;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class Form extends AbstractEntity {
	const EMAIL = "email";
	const EMAIL_CONFIRMATION = "email_confirmation";
	const FIRST_NAME = "first_name";
	const LAST_NAME = "last_name";
	const FULL_NAME = "full_name";
	const PHONE = "phone";
	const PHONE_AREA = "phone_area";
	const PHONE_PREFIX = "phone_prefix";
	const PHONE_LOCAL = "phone_local";
	const DATE_OF_BIRTH = "date_of_birth";
	const DATE_OF_BIRTH_DAY = "date_of_birth_day";
	const DATE_OF_BIRTH_MONTH = "date_of_birth_month";
	const DATE_OF_BIRTH_YEAR = "date_of_birth_year";
	const AGE = "age";
	const GENDER = "gender";
	const CITIZENSHIP = "citizenship";
	const CITIZENSHIP_NAME = "citizenship_name";
	const ETHNICITY = "ethnicity";
	const ETHNICITY_NAME = "ethnicity_name";
	const PICTURE = "picture";
	const COUNTRY = "country";
	const COUNTRY_ABBREVIATION = "country_abbreviation";
	const STATE = "state";
	const STATE_ABBREVIATION = "state_abbreviation";
	const CITY = "city";
	const ADDRESS = "address";
	const ZIP = "zip";
	const SCHOOL_LEVEL = "school_level";
	const SCHOOL_LEVEL_NAME = "school_level_name";
	const DEGREE = "degree";
	const DEGREE_NAME = "degree_name";
	const DEGREE_TYPE = "degree_type";
	const DEGREE_TYPE_NAME = "degree_type_name";
	const ENROLLMENT_YEAR = "enrollment_year";
	const ENROLLMENT_MONTH = "enrollment_month";
	const GRADUATION_YEAR = "graduation_year";
	const GRADUATION_MONTH = "graduation_month";
	const GPA = "gpa";
	const GPA_RANGE = "gpa_range";
	const CAREER_GOAL = "career_goal";
	const CAREER_GOAL_NAME = "career_goal_name";
	const STUDY_ONLINE = "study_online";
	const HIGHSCHOOL = "highschool";
	const UNIVERSITY = "university";
	const ACCEPT_CONFIRMATION = "accept_confirmation";
	const HIDDEN_FIELD = "hidden_field";
	const STATIC_FIELD = "static_field";
	const SUBMIT_FIELD = "submit_field";
	const UPLOAD_FIELD = "upload_field";
	const ESSAY = "essay";
	
	private $formId;
	private $scholarshipId;
	private $formField;
	private $systemField;
	private $value;
	private $mapping;
	
	
	public function __construct() {
		$this->formId = 0;
		$this->scholarshipId = 0;
		$this->formField = "";
		$this->systemField = "";
		$this->value = "";
		$this->mapping = array();
	}
	
	public function getFormId(){
		return $this->formId;
	}
	
	public function setFormId($formId){
		$this->formId = $formId;
	}
	
	public function getScholarshipId(){
		return $this->scholarshipId;
	}
	
	public function setScholarshipId($scholarshipId){
		$this->scholarshipId = $scholarshipId;
	}
	
	public function getFormField(){
		return $this->formField;
	}
	
	public function setFormField($formField){
		$this->formField = $formField;
	}
	
	public function getSystemField(){
		return $this->systemField;
	}
	
	public function setSystemField($systemField){
		$this->systemField = $systemField;
	}
	
	public function getValue(){
		return $this->value;
	}
	
	public function setValue($value){
		$this->value = $value;
	}
	
	public function getMapping(){
		return $this->mapping;
	}
	
	public function setMapping($mapping){
		$this->mapping = $mapping;
	}

	public static function getSystemFields() {
		return array(
			self::EMAIL => "Email",
			self::EMAIL_CONFIRMATION => "Email Confirmation",
			self::FIRST_NAME => "First Name",
			self::LAST_NAME => "Last Name",
			self::FULL_NAME => "Full Name",
			self::PHONE => "Phone",
			self::PHONE_AREA => "Phone Area Code",
			self::PHONE_PREFIX => "Phone Prefix",
			self::PHONE_LOCAL => "Phone Local",
			self::DATE_OF_BIRTH => "Date Of Birth",
			self::DATE_OF_BIRTH_DAY => "Day Of Birth",
			self::DATE_OF_BIRTH_MONTH => "Month Of Birth",
			self::DATE_OF_BIRTH_YEAR => "Year Of Birth",
			self::AGE => "Age",
			self::GENDER => "Gender",
			self::CITIZENSHIP => "Citizenship",
			self::CITIZENSHIP_NAME => "Citizenship Name",
			self::ETHNICITY => "Ethnicity",
			self::ETHNICITY_NAME => "Ethnicity Name",
			self::PICTURE => "Picture",
			self::COUNTRY => "Country",
			self::COUNTRY_ABBREVIATION => "Country Abbreviation",
			self::STATE => "State",
			self::STATE_ABBREVIATION => "State Abbreviation",
			self::CITY => "City",
			self::ADDRESS => "Address",
			self::ZIP => "Zip",
			self::SCHOOL_LEVEL => "School Level",
			self::SCHOOL_LEVEL_NAME => "School Level Name",
			self::DEGREE => "Degree",
			self::DEGREE_NAME => "Degree Name",
			self::DEGREE_TYPE => "Degree Type",
			self::DEGREE_TYPE_NAME => "Degree Type Name",
			self::ENROLLMENT_YEAR => "Enrollment Year",
			self::ENROLLMENT_MONTH => "Enrollment Month",
			self::GRADUATION_YEAR => "Graduation Year",
			self::GRADUATION_MONTH => "Graduation Month",
			self::GPA => "GPA",
			self::GPA_RANGE => "GPA Range",
			self::CAREER_GOAL => "Career Goal",
			self::CAREER_GOAL_NAME => "Career Goal Name",
			self::STUDY_ONLINE => "Study Online",
			self::HIGHSCHOOL => "High School",
			self::UNIVERSITY => "University",
			self::ACCEPT_CONFIRMATION => "Accept Confirmation",
			self::HIDDEN_FIELD => "Hidden Field",
			self::STATIC_FIELD => "Static Field",
			self::SUBMIT_FIELD => "Submit Field",
		);
	}
	
	public function toArray() {
		return array(
			"form_id" => $this->getFormId(),
			"scholarship_id" => $this->getScholarshipId(),
			"form_field" => $this->getFormField(),
			"system_field" => $this->getSystemField(),
			"value" => $this->getValue(),
			"mapping" => $this->getMapping(),
		);
	}
	
	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "form_id") {
				$this->setFormId($value);
			}
			else if ($key == "scholarship_id") {
				$this->setScholarshipId($value);
			}
			else if ($key == "form_field") {
				$this->setFormField($value);
			}
			else if ($key == "system_field") {
				$this->setSystemField($value);
			}
			else if ($key == "value") {
				$this->setValue($value);
			}
			else if ($key == "mapping") {
				$this->setMapping($value);
			}
		}
	}
}
