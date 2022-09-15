<?php

/**
 * Profile
 *
 * @package     ScholarshipOwl\Data\Entity\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Account;

use Carbon\Carbon;
use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Info\CareerGoal;
use ScholarshipOwl\Data\Entity\Info\Citizenship;
use ScholarshipOwl\Data\Entity\Info\Country;
use ScholarshipOwl\Data\Entity\Info\Degree;
use ScholarshipOwl\Data\Entity\Info\DegreeType;
use ScholarshipOwl\Data\Entity\Info\Ethnicity;
use ScholarshipOwl\Data\Entity\Info\MilitaryAffiliation;
use ScholarshipOwl\Data\Entity\Info\State;
use ScholarshipOwl\Data\Entity\Info\SchoolLevel;
use ScholarshipOwl\Data\Service\Info\HighschoolService;
use ScholarshipOwl\Data\Service\Info\StateService;
use ScholarshipOwl\Data\Service\Info\UniversityService;


class Profile extends AbstractEntity {
	const TOTAL_PROFILE_FIELDS = 25;

	const GENDER_FEMALE = "female";
	const GENDER_MALE = "male";
    const GENDER_OTHER = "other";

	const GPA_NOT_AVAILABLE = "N/A";

	const STUDY_ONLINE_YES = "yes";
	const STUDY_ONLINE_NO = "no";
	const STUDY_ONLINE_MAYBE = "maybe";

    const RECURRENT_APPLY_DISABLED = 0;
    const RECURRENT_APPLY_ASAP = 1;
    const RECURRENT_APPLY_ON_DEADLINE = 2;


	private $accountId;
	private $firstName;
	private $lastName;
	private $phone;
	private $dateOfBirth;
	private $gender;
	private $citizenship;
	private $ethnicity;
	private $subscribed;
	private $avatar;
	private $agreeCall;

	private $country;
	private $state;
	private $stateName;
	private $city;
	private $address;
	private $address2;
	private $zip;

	private $schoolLevel;
	private $degree;
	private $degreeType;
	private $enrollmentYear;
	private $enrollmentMonth;

	private $gpa;
	private $careerGoal;
	private $studyOnline;

	private $graduationYear;
	private $graduationMonth;
	private $highschoolGraduationYear;
	private $highschoolGraduationMonth;
	private $highSchool;
    private $highschoolAddress1;
    private $highschoolAddress2;
    private $enrolled;
	private $university;
    private $universityAddress1;
    private $universityAddress2;
    private $university1;
    private $university2;
    private $university3;
    private $university4;

    private $studyCountry1;
    private $studyCountry2;
    private $studyCountry3;
    private $studyCountry4;
    private $studyCountry5;

    private $militaryAffiliation;

	private $profileType;

    private $recurringApplication;

	public function __construct($accountId = null) {
		$this->accountId = $accountId;
		$this->firstName = "";
		$this->lastName = "";
		$this->phone = "";
		$this->dateOfBirth = "0000-00-00 00:00:00";
		$this->gender = null;
		$this->citizenship = new Citizenship();
		$this->ethnicity = new Ethnicity();
		$this->subscribed = 0;
		$this->avatar = "";

		$this->country = new Country();
		$this->state = new State();
		$this->stateName = "";
		$this->city = "";
		$this->address = "";
		$this->address2 = "";
		$this->zip = "";

		$this->schoolLevel = new SchoolLevel();
		$this->degree = new Degree();
		$this->degreeType = new DegreeType();
		$this->enrollmentMonth = null;
		$this->enrollmentYear = null;

		$this->gpa = self::GPA_NOT_AVAILABLE;
		$this->careerGoal = new CareerGoal();
		$this->studyOnline = self::STUDY_ONLINE_NO;

		$this->graduationYear = null;
		$this->graduationMonth = null;
		$this->highschoolGraduationYear = null;
		$this->highschoolGraduationMonth = null;
		$this->highSchool = "";
        $this->enrolled = null;
		$this->university = "";
        $this->university1 = "";
        $this->university2 = "";
        $this->university3 = "";
        $this->university4 = "";

		$this->militaryAffiliation = new MilitaryAffiliation();
		$this->profileType = "";

        $this->recurringApplication = 0;

        $this->studyCountry1 = new Country();
        $this->studyCountry2 = new Country();
        $this->studyCountry3 = new Country();
        $this->studyCountry4 = new Country();
        $this->studyCountry5 = new Country();
	}

	public function getAccountId(){
		return $this->accountId;
	}

	public function setAccountId($accountId){
		$this->accountId = $accountId;
	}

	public function getFirstName(){
		return ucwords(strtolower($this->firstName));
	}

	public function setFirstName($firstName){
		$this->firstName = $firstName;
	}

	public function getLastName(){
		return ucwords(strtolower($this->lastName));
	}

	public function setLastName($lastName){
		$this->lastName = $lastName;
	}

	public function getPhone(){
		return $this->phone;
	}

	public function setPhone($phone){
		$this->phone = $phone;
	}

	public function getDateOfBirth(){
		return $this->dateOfBirth;
	}

	public function setDateOfBirth($dateOfBirth){
		$this->dateOfBirth = $dateOfBirth;
	}

	public function getGender(){
        return ucfirst(strtolower($this->gender));
	}

	public function getGenderAbbreviation(){
		return strtoupper($this->gender[0]);
	}

	public function setGender($gender){
		$this->gender = strtolower($gender);
	}

	public function getCitizenship(){
		return $this->citizenship;
	}

	public function setCitizenship(Citizenship $citizenship){
		$this->citizenship = $citizenship;
	}

	public function getEthnicity(){
		return $this->ethnicity;
	}

	public function setEthnicity(Ethnicity $ethnicity){
		$this->ethnicity = $ethnicity;
	}

	public function isSubscribed(){
		return $this->subscribed;
	}

	public function setSubscribed($subscribed){
		$this->subscribed = $subscribed;
	}

	public function getAvatar(){
		return $this->avatar;
	}

	public function setAvatar($avatar){
		$this->avatar = $avatar;
	}

	public function getCountry(){
		return $this->country;
	}

	public function setCountry(Country $country){
		$this->country = $country;
	}

	public function getState(){
		return $this->state;
	}

	public function setState(State $state){
		$this->state = $state;
	}

    /**
     * @return string|null
     */
    public function getStateName()
    {
        return $this->stateName;
    }

    /**
     * @param string|null $stateName
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;
    }

	public function getCity(){
		return $this->city;
	}

	public function setCity($city){
		$this->city = $city;
	}

	public function getAddress(){
		return $this->address;
	}

	public function setAddress($address){
		$this->address = $address;
	}

    /**
     * @return string|null
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * @param string|null $address2
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;
    }

	public function getZip(){
		return $this->zip;
	}

	public function setZip($zip){
		$this->zip = $zip;
	}

	public function getSchoolLevel(){
		return $this->schoolLevel;
	}

	public function setSchoolLevel(SchoolLevel $schoolLevel){
		$this->schoolLevel = $schoolLevel;
	}

	public function getDegree(){
		return $this->degree;
	}

	public function setDegree(Degree $degree){
		$this->degree = $degree;
	}

	public function getDegreeType(){
		return $this->degreeType;
	}

	public function setDegreeType(DegreeType $degreeType){
		$this->degreeType = $degreeType;
	}

	public function getEnrollmentYear(){
		return $this->enrollmentYear;
	}

	public function setEnrollmentYear($enrollmentYear){
		$this->enrollmentYear = $enrollmentYear;
	}

	public function getEnrollmentMonth(){
		return $this->enrollmentMonth;
	}

	public function setEnrollmentMonth($enrollmentMonth){
		$this->enrollmentMonth = $enrollmentMonth;
	}

	public function getGpa(){
		return $this->gpa;
	}

	public function setGpa($gpa){
		$this->gpa = $gpa;
	}

	public function getCareerGoal() {
		return $this->careerGoal;
	}

	public function setCareerGoal(CareerGoal $careerGoal) {
		$this->careerGoal = $careerGoal;
	}

	public function getStudyOnline(){
		return $this->studyOnline;
	}

	public function setStudyOnline($studyOnline){
		$this->studyOnline = $studyOnline;
	}

	public function getGraduationMonth(){
		return $this->graduationMonth;
	}

	public function setGraduationMonth($graduationMonth){
		$this->graduationMonth = $graduationMonth;
	}

	public function setGraduationYear($graduationYear){
		$this->graduationYear = $graduationYear;
	}

	public function getGraduationYear(){
		return $this->graduationYear;
	}

	public function setHighschoolGraduationMonth($graduationMonth){
		$this->highschoolGraduationMonth = $graduationMonth;
	}

	public function getHighschoolGraduationMonth(){
		return $this->highschoolGraduationMonth;
	}

	public function setHighschoolGraduationYear($graduationYear){
		$this->highschoolGraduationYear = $graduationYear;
	}

	public function getHighschoolGraduationYear(){
		return $this->highschoolGraduationYear;
	}

	public function getHighSchool(){
		return $this->highSchool;
	}

	public function setHighSchool($highSchool){
        $highschoolService = new HighschoolService();
        if(is_numeric($highSchool)){
            $this->highSchool = $highschoolService->getHighschoolName($highSchool);
        } else if(is_string($highSchool)){
            $this->highSchool = $highSchool;
        }
    }

    public function getHighschoolAddress1()
    {
        return $this->highschoolAddress1;
    }

    public function setHighschoolAddress1($highschoolAddress1)
    {
        $this->highschoolAddress1 = $highschoolAddress1;
    }

    public function getHighschoolAddress2()
    {
        return $this->highschoolAddress2;
    }

    public function setHighschoolAddress2($highschoolAddress2)
    {
        $this->highschoolAddress2 = $highschoolAddress2;
    }

    public function getEnrolled() {
        return $this->enrolled;
    }

    public function setEnrolled($enrolled) {
        $this->enrolled = $enrolled;
    }

	public function getUniversity(){
		return $this->university;
	}

    public function getUniversityAddress1()
    {
        return $this->universityAddress1;
    }

    public function setUniversityAddress1($universityAddress1)
    {
        $this->universityAddress1 = $universityAddress1;
    }

    public function getUniversityAddress2()
    {
        return $this->universityAddress2;
    }

    public function setUniversityAddress2($universityAddress2)
    {
        $this->universityAddress2 = $universityAddress2;
    }

    public function getUniversity1(){
        return $this->university1;
    }

    public function getUniversity2(){
        return $this->university2;
    }

    public function getUniversity3(){
        return $this->university3;
    }

    public function getUniversity4(){
        return $this->university4;
    }

	public function setUniversity($university){
		$this->university = $university;
	}

    public function setUniversity1($university){
        $this->university1 = $university;
    }
    public function setUniversity2($university){
        $this->university2 = $university;
    }
    public function setUniversity3($university){
        $this->university3 = $university;
    }
    public function setUniversity4($university){
        $this->university4 = $university;
    }

    public function setUniversities($universities){
        $universityService = new UniversityService();
        $counter = 0;
        foreach($universities as $university){
            if(is_numeric($university)){
                $name = $universityService->getUniversityName($university);
            } else if(is_string($university)){
                $name = $university;
            }
            if($counter == 0){
                $this->university = $name;
            } else {
                $fieldName = "university".$counter;
                $this->{$fieldName} = $name;
            }
            $counter++;
            if($counter > 4) return;
        }
    }

	public function getMilitaryAffiliation(){
		return $this->militaryAffiliation;
	}

	public function setMilitaryAffiliation(MilitaryAffiliation $militaryAffiliation){
		$this->militaryAffiliation = $militaryAffiliation;
	}

	public function getProfileType() {
		return $this->profileType;
	}

	public function setProfileType($profileType) {
		$this->profileType = $profileType;
	}

    public function getAgreeCall(){
        return $this->agreeCall;
    }

    public function setAgreeCall($agreeCall){
        $this->agreeCall = $agreeCall;
    }

    public function getRecurringApplication(){
        return $this->recurringApplication;
    }

    public function setRecurringApplication($recurringApplication){
        $this->recurringApplication = $recurringApplication;
    }

	public function getFullName() {
		return sprintf("%s %s", $this->getFirstName(), $this->getLastName());
	}

	public function getProfileCompleteness() {
		$count = 0;

		$fields = array(
			"firstName", "lastName", "phone", "gender", "city", "address", "zip",
			"studyOnline", "enrollmentYear", "enrollmentMonth", "graduationYear", "graduationMonth",
			"highSchool", "university", "militaryAffiliation"
		);

		foreach($fields as $field) {
			$value = trim($this->$field);

			if(!empty($value)) {
				$count++;
			}
		}

		if(!empty($this->gpa) && $this->gpa != "N/A") {
			$count++;
		}

		if(!empty($this->dateOfBirth) && $this->dateOfBirth != "0000-00-00 00:00:00") {
			$count++;
		}

		$data = array();
		$data["careerGoalId"] = $this->getCareerGoal()->getCareerGoalId();
		$data["citizenshipId"] = $this->getCitizenship()->getCitizenshipId();
		$data["ethnicityId"] = $this->getEthnicity()->getEthnicityId();
		$data["countryId"] = $this->getCountry()->getCountryId();
		$data["stateId"] = $this->getState()->getStateId() ?: $this->getStateName();
		$data["schoolLevelId"] = $this->getSchoolLevel()->getSchoolLevelId();
		$data["degreeId"] = $this->getDegree()->getDegreeId();
		$data["degreeTypeId"] = $this->getDegreeType()->getDegreeTypeId();

		foreach($data as $key => $value) {
			$value = trim($value);

			if(!empty($value)) {
				$count++;
			}
		}

		return floor((($count) * 100) / self::TOTAL_PROFILE_FIELDS);
	}

	public function getAge() {
		$result = "";

		if(!empty($this->dateOfBirth) && $this->dateOfBirth != "0000-00-00 00:00:00") {
            $result = Carbon::createFromFormat('Y-m-d H:i:s', $this->dateOfBirth)->age;
		}

		return $result;
	}

	public function getDateOfBirthYear() {
		$result = "";

		if(!empty($this->dateOfBirth) && $this->dateOfBirth != "0000-00-00 00:00:00") {
			$result = date("Y", strtotime($this->dateOfBirth));
		}

		return $result;
	}

	public function getDateOfBirthMonth($leadingZero = true) {
		$result = "";

		if(!empty($this->dateOfBirth) && $this->dateOfBirth != "0000-00-00 00:00:00") {
			$result = date($leadingZero ? 'm' : 'n', strtotime($this->dateOfBirth));
		}

		return $result;
	}

	public function getDateOfBirthDay($leadingZero = true) {
		$result = "";

		if(!empty($this->dateOfBirth) && $this->dateOfBirth != "0000-00-00 00:00:00") {
			$result = date($leadingZero ? 'd' : 'j', strtotime($this->dateOfBirth));
		}

		return $result;
	}

	public function getPhoneAreaCode() {
		$result = "";

		if(!empty($this->phone)) {
			$result = substr($this->phone, 0, 3);
		}

		return $result;
	}

	public function getPhonePrefix() {
		$result = "";

		if(!empty($this->phone)) {
			$result = substr($this->phone, 3, 3);
		}

		return $result;
	}

    /**
     * @param Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry1($studyCountry)
    {
        $this->studyCountry1 = $studyCountry;

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry1()
    {
        return $this->studyCountry1;
    }

    /**
     * @param Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry2($studyCountry)
    {
        $this->studyCountry2 = $studyCountry;

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry2()
    {
        return $this->studyCountry2;
    }

    /**
     * @param Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry3($studyCountry)
    {
        $this->studyCountry3 = $studyCountry;

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry3()
    {
        return $this->studyCountry3;
    }

    /**
     * @param Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry4($studyCountry)
    {
        $this->studyCountry4 = $studyCountry;

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry4()
    {
        return $this->studyCountry4;
    }

    /**
     * @param Country $studyCountry
     *
     * @return $this
     */
    public function setStudyCountry5($studyCountry)
    {
        $this->studyCountry5 = $studyCountry;

        return $this;
    }

    /**
     * @return Country
     */
    public function getStudyCountry5()
    {
        return $this->studyCountry5;
    }

	public function getPhoneLocal() {
		$result = "";

		if(!empty($this->phone)) {
			$result = substr($this->phone, 6, 4);
		}

		return $result;
	}

	public static function getGenders() {
		return array(
			self::GENDER_FEMALE => "Female",
			self::GENDER_MALE => "Male",
            self::GENDER_OTHER => "Other",
		);
	}

	public static function getStudyOnlineOptions() {
		return array(
			self::STUDY_ONLINE_YES => "Yes",
			self::STUDY_ONLINE_NO => "No",
			self::STUDY_ONLINE_MAYBE => "Maybe"
		);
	}

	public static function getRecurringApplicationOptions() {
		return array(
			self::RECURRENT_APPLY_DISABLED => "Do not apply automatically",
			self::RECURRENT_APPLY_ASAP => "Apply as soon as new scholarship is available",
			self::RECURRENT_APPLY_ON_DEADLINE => "Apply on deadline",
		);
	}

	public static function getMonthsArray($options = null) {
		$result = array_combine(range(1, 12), range(1, 12));

		if(isset($options)) {
			$result = $options + $result;
		}

		return $result;
	}

	public static function getDaysArray($options = null) {
		$result = array_combine(range(1, 31), range(1, 31));

		if(isset($options)) {
			$result = $options + $result;
		}

		return $result;
	}

	public static function getYearsArray($options = null) {
		$result = array_combine(range(date("Y") - 16, 1900, -1), range(date("Y") - 16, 1900, -1));

		if(isset($options)) {
			$result = $options + $result;
		}

		return $result;
	}

	public static function getFutureYearsArray($options = null, $distance = 30) {
		$result = array_combine(range(date("Y") + $distance, 1950, -1), range(date("Y") + $distance, 1950, -1));

		if(isset($options)) {
			$result = $options + $result;
		}

		return $result;
	}

	public static function getGpaArray($options = null) {
		$result = array();

		for($i = 2.0; $i <= 4.1; $i += 0.1) {
			$key = sprintf("%.01f", $i);
			$result[$key] = $key;
		}

		if(isset($options)) {
			$result = $options + $result;
		}

		return $result;
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "account_id") {
				$this->setAccountId($value);
			}
			else if($key == "first_name") {
				$this->setFirstName($value);
			}
			else if($key == "last_name") {
				$this->setLastName($value);
			}
			else if($key == "phone") {
				$this->setPhone($value);
			}
			else if($key == "date_of_birth") {
				$this->setDateOfBirth($value);
			}
			else if($key == "gender") {
				$this->setGender($value);
			}
			else if($key == "citizenship_id") {
				$this->getCitizenship()->setCitizenshipId($value);
			}
			else if($key == "ethnicity_id") {
				$this->getEthnicity()->setEthnicityId($value);
			}
			else if($key == "is_subscribed") {
				$this->setSubscribed($value);
			}
			else if($key == "avatar") {
				$this->setAvatar($value);
			}
			else if($key == "country_id") {
				$this->getCountry()->setCountryId($value);
			}
            else if($key == "country_code") {
                $this->getCountry()->setCountryId(
                    \App\Entity\Country::findByCountryCode($value ? $value : \App\Entity\Country::getCountryCodeByIP())
                        ->getId()
                );
            }
			else if($key == "state_id") {
				$this->getState()->setStateId($value);
			}
			else if($key == "state_name") {
				$this->setStateName($value);
			}
			else if($key == "city") {
				$this->setCity($value);
			}
			else if($key == "address") {
				$this->setAddress($value);
			}
			else if($key == "address2") {
				$this->setAddress2($value);
			}
			else if($key == "zip") {
				$this->setZip($value);
			}
			else if($key == "school_level_id") {
				$this->getSchoolLevel()->setSchoolLevelId($value);
			}
			else if($key == "degree_id") {
				$this->getDegree()->setDegreeId($value);
			}
			else if($key == "degree_type_id") {
				$this->getDegreeType()->setDegreeTypeId($value);
			}
			else if($key == "enrollment_year") {
				$this->setEnrollmentYear($value);
			}
			else if($key == "enrollment_month") {
				$this->setEnrollmentMonth($value);
			}
			else if($key == "gpa") {
				$this->setGpa($value);
			}
			else if($key == "career_goal_id") {
				$this->getCareerGoal()->setCareerGoalId($value);
			}
			else if($key == "study_online") {
				$this->setStudyOnline($value);
			}
			else if($key == "graduation_year") {
				$this->setGraduationYear($value);
			}
			else if($key == "graduation_month") {
				$this->setGraduationMonth($value);
			}
			else if($key == "highschool_graduation_year") {
				$this->setHighschoolGraduationYear($value);
			}
			else if($key == "highschool_graduation_month") {
				$this->setHighschoolGraduationMonth($value);
			}
			else if($key == "highschool") {
				$this->setHighSchool($value);
			}
            else if ($key === 'highschool_address1') {
                $this->setHighschoolAddress1($value);
            }
            else if ($key === 'highschool_address2') {
                $this->setHighschoolAddress2($value);
            }
            else if($key == "enrolled") {
                $this->setEnrolled($value);
            }
			else if($key == "university") {
				$this->setUniversity($value);
			}
            else if ($key === 'university_address1') {
                $this->setUniversityAddress1($value);
            }
            else if ($key === 'university_address2') {
                $this->setUniversityAddress2($value);
            }
            else if($key == "university1") {
                $this->setUniversity1($value);
            }
            else if($key == "university2") {
                $this->setUniversity2($value);
            }
            else if($key == "university3") {
                $this->setUniversity3($value);
            }
            else if($key == "university4") {
                $this->setUniversity4($value);
            }
            else if($key == "college") {
                $this->setUniversities($value);
            }
            else if($key == "military_affiliation_id") {
                $this->getMilitaryAffiliation()->setMilitaryAffiliationId($value);
            }
			else if($key == "profile_type") {
				$this->setProfileType($value);
			}
			else if($key == "agree_call") {
				$this->setAgreeCall($value);
			}
			else if($key == "recurring_application") {
				$this->setRecurringApplication($value);
			}
            else if($key == "study_country1") {
                $this->getStudyCountry1()->setCountryId($value);
            }
            else if($key == "study_country2") {
                $this->getStudyCountry2()->setCountryId($value);
            }
            else if($key == "study_country3") {
                $this->getStudyCountry3()->setCountryId($value);
            }
            else if($key == "study_country4") {
                $this->getStudyCountry4()->setCountryId($value);
            }
            else if($key == "study_country5") {
                $this->getStudyCountry5()->setCountryId($value);
            }
            else if($key == "study_country") {
                $this->getStudyCountry1()->setCountryId($value[0]['id'] ?? null);
                $this->getStudyCountry2()->setCountryId($value[1]['id'] ?? null);
                $this->getStudyCountry3()->setCountryId($value[2]['id'] ?? null);
                $this->getStudyCountry4()->setCountryId($value[3]['id'] ?? null);
                $this->getStudyCountry5()->setCountryId($value[4]['id'] ?? null);
            }
		}
	}

	public function toArray() {
		return array(
			"account_id" => $this->getAccountId(),
			"first_name" => $this->getFirstName(),
			"last_name" => $this->getLastName(),
			"phone" => $this->getPhone(),
			"date_of_birth" => $this->getDateOfBirth(),
			"gender" => strtolower($this->getGender()),
			"citizenship_id" => $this->getCitizenship()->getCitizenshipId(),
			"ethnicity_id" => $this->getEthnicity()->getEthnicityId(),
			"is_subscribed" => $this->isSubscribed(),
			"avatar" => $this->getAvatar(),
			"country_id" => $this->getCountry()->getCountryId(),
			"state_id" => $this->getState()->getStateId(),
			"state_name" => $this->getStateName(),
			"city" => $this->getCity(),
			"address" => $this->getAddress(),
			"address2" => $this->getAddress2(),
			"zip" => $this->getZip(),
			"school_level_id" => $this->getSchoolLevel()->getSchoolLevelId(),
			"degree_id" => $this->getDegree()->getDegreeId(),
			"degree_type_id" => $this->getDegreeType()->getDegreeTypeId(),
			"enrollment_year" => $this->getEnrollmentYear(),
			"enrollment_month" => $this->getEnrollmentMonth(),
			"gpa" => $this->getGpa(),
			"career_goal_id" => $this->getCareerGoal()->getCareerGoalId(),
			"graduation_year" => $this->getGraduationYear(),
			"graduation_month" => $this->getGraduationMonth(),
			"highschool_graduation_year" => $this->getHighschoolGraduationYear(),
			"highschool_graduation_month" => $this->getHighschoolGraduationMonth(),
			"study_online" => $this->getStudyOnline(),
			"highschool" => $this->getHighSchool(),
            'highschool_address1' => $this->getHighschoolAddress1(),
            'highschool_address2' => $this->getHighschoolAddress2(),
            "enrolled" => $this->getEnrolled(),
			"university" => $this->getUniversity(),
            'university_address1' => $this->getUniversityAddress1(),
            'university_address2' => $this->getUniversityAddress2(),
            "university1" => $this->getUniversity1(),
            "university2" => $this->getUniversity2(),
            "university3" => $this->getUniversity3(),
            "university4" => $this->getUniversity4(),
            "military_affiliation_id" => $this->getMilitaryAffiliation()->getMilitaryAffiliationId(),
			"profile_type" => $this->getProfileType(),
			"agree_call" => $this->getAgreeCall(),
			"recurring_application" => $this->getRecurringApplication(),
            'study_country1' => $this->getStudyCountry1()->getCountryId(),
            'study_country2' => $this->getStudyCountry2()->getCountryId(),
            'study_country3' => $this->getStudyCountry3()->getCountryId(),
            'study_country4' => $this->getStudyCountry4()->getCountryId(),
            'study_country5' => $this->getStudyCountry5()->getCountryId(),
		);
	}
}
