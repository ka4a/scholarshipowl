<?php

/**
 * Field
 *
 * @package     ScholarshipOwl\Data\Entity\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	17. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Info;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class Field extends AbstractEntity {
	const EMAIL = 1;
	const FIRST_NAME = 2;
	const LAST_NAME = 3;
	const PHONE = 4;
	const DATE_OF_BIRTH = 5;
	const AGE = 6;
	const BIRTHDAY_YEAR = 7;
	const BIRTHDAY_MONTH = 8;
	const BIRTHDAY_DAY = 9;
	const GENDER = 10;
	const CITIZENSHIP = 11;
	const ETHNICITY = 12;
	const PICTURE = 13;
	const COUNTRY = 14;
	const STATE = 15;
	const CITY = 16;
	const ADDRESS = 17;
	const ZIP = 18;
	const SCHOOL_LEVEL = 19;
	const DEGREE = 20;
	const DEGREE_TYPE = 21;
	const ENROLLMENT_YEAR = 22;
	const ENROLLMENT_MONTH = 23;
	const GPA = 24;
	const CAREER_GOAL = 25;
	const STUDY_ONLINE = 26;
	const HIGH_SCHOOL_NAME = 27;
	const HIGH_SCHOOL_GRADUATION_YEAR = 28;
	const HIGH_SCHOOL_GRADUATION_MONTH = 29;
	const HIGH_SCHOOL_COUNTRY = 30;
	const HIGH_SCHOOL_STATE = 31;
	const HIGH_SCHOOL_CITY = 32;
	const HIGH_SCHOOL_ADDRESS = 33;
	const HIGH_SCHOOL_ZIP = 34;
	const COLLEGE_NAME = 35;
	const COLLEGE_GRADUATION_YEAR = 36;
	const COLLEGE_GRADUATION_MONTH = 37;
	const COLLEGE_COUNTRY = 38;
	const COLLEGE_STATE = 39;
	const COLLEGE_CITY = 40;
	const COLLEGE_ADDRESS = 41;
	const COLLEGE_ZIP = 42;
	const ACCEPT_CONFIRMATION = 43;
	const EMAIL_CONFIRMATION = 44;
	const PHONE_AREA_CODE = 45;
	const PHONE_PREFIX = 46;
	const PHONE_LOCAL = 47;
	const FULL_NAME = 48;
	const ACCEPT_CONFIRMATION_2 = 49;
	const ACCEPT_CONFIRMATION_3 = 50;
	const ACCEPT_CONFIRMATION_4 = 51;
	const ACCEPT_CONFIRMATION_5 = 52;
	const STATE_ABBREVIATION = 53;
	const HIDDEN_FIELD_1 = 54;
	const HIDDEN_FIELD_2 = 55;
	const HIDDEN_FIELD_3 = 56;
	const HIDDEN_FIELD_4 = 57;
	const HIDDEN_FIELD_5 = 58;
	const STATIC_FIELD_1 = 59;
	const STATIC_FIELD_2 = 60;
	const STATIC_FIELD_3 = 61;
	const STATIC_FIELD_4 = 62;
	const STATIC_FIELD_5 = 63;
	const MILITARY_AFFILIATION = 64;
    const COUNTRY_OF_STUDY = 65;
    const STATE_NAME = 66;

	private $fieldId;
	private $name;


	public function __construct($fieldId = null) {
		$this->fieldId = $fieldId;
		$this->name = "";
	}

	public function setFieldId($fieldId) {
		$this->fieldId = $fieldId;
	}

	public function getFieldId() {
		return $this->fieldId;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function __toString() {
		return $this->name;
	}

	// Array of $fieldId => $infoClass
	public static function getMultiValues() {
		return array(
			Field::GENDER => "Gender",
			Field::CITIZENSHIP => "Citizenship",
			Field::ETHNICITY => "Ethnicity",
			Field::COUNTRY => "Country",
			Field::STATE => "State",
			Field::SCHOOL_LEVEL => "SchoolLevel",
			Field::DEGREE => "Degree",
			Field::DEGREE_TYPE => "DegreeType",
			Field::GPA => "GPA",
			Field::MILITARY_AFFILIATION => "MilitaryAffiliation",
            Field::COUNTRY_OF_STUDY => 'StudyCountry',
		);
	}

	public static function getTemplateNames() {
		return array(
			"email" => "Email",
			"email_confirmation" => "Email Confirmation",
			"first_name" => "First Name",
			"last_name" => "Last Name",
			"full_name" => "Full Name",
			"phone" => "Phone",
			"phone_area" => "Phone Area Code",
			"phone_prefix" => "Phone Prefix",
			"phone_local" => "Phone Local",
			"date_of_birth" => "Date Of Birth",
			"date_of_birth_day" => "Day Of Birth",
			"date_of_birth_month" => "Month Of Birth",
			"date_of_birth_year" => "Year Of Birth",
			"age" => "Age",
			"gender" => "Gender",
			"citizenship" => "Citizenship",
			"ethnicity" => "Ethnicity",
			"picture" => "Picture",
			"country" => "Country",
			"country_abbreviation" => "Country Abbreviation",
			"state" => "State",
			"state_abbreviation" => "State Abbreviation",
			"state_name" => "State Name",
			"city" => "City",
			"address" => "Address",
			"zip" => "Zip",
			"school_level" => "School Level",
			"degree" => "Degree",
			"degree_type" => "Degree Type",
			"enrollment_year" => "Enrollment Year",
			"enrollment_month" => "Enrollment Month",
			"gpa" => "GPA",
			"gpa_range" => "GPA Range",
			"career_goal" => "Career Goal",
			"study_online" => "Study Online",
			"highschool" => "High School",
			"university" => "University",
			"accept_confirmation" => "Accept Confirmation",
			"hidden_field" => "Hidden Field",
			"static_field" => "Static Field",
			"essay" => "Essay",
			"military_affiliation" => "Military Affiliation",
		);
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "field_id") {
				$this->setFieldId($value);
			}
			else if($key == "name") {
				$this->setName($value);
			}
		}
	}

	public function toArray() {
		return array(
			"field_id" => $this->getFieldId(),
			"name" => $this->getName()
		);
	}
}
