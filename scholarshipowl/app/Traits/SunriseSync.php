<?php

namespace App\Traits;

use App\Entity\Account;
use App\Entity\CareerGoal;
use App\Entity\Citizenship;
use App\Entity\Country;
use App\Entity\Degree;
use App\Entity\DegreeType;
use App\Entity\Eligibility;
use App\Entity\Ethnicity;
use App\Entity\Field;
use App\Entity\MilitaryAffiliation;
use App\Entity\ScholarshipStatus;
use App\Entity\SchoolLevel;
use App\Entity\State;

trait SunriseSync
{
    /**
     * Map Sunrise's scholarship statuses to SOWL's
     *
     * @return array
     */
    public function statusMap()
    {
        return [
            'published' => ScholarshipStatus::PUBLISHED,
            'unpublished' => ScholarshipStatus::UNPUBLISHED,
            'expired' => ScholarshipStatus::EXPIRED,
            'unprocessable' => ScholarshipStatus::UNPROCESSABLE,
        ];
    }

    /**
     * @param $fields Array of Sunrise's eligibilities
     */
    public function normalizeEligibilities(& $fields)
    {
        foreach ($fields as & $data) {
            $operator = $data['eligibilityType'] ?? '';

            $isBool = $data['field']['type'] === 'option'
                && count($data['field']['options']) === 2
                && $data['field']['options'][1] === 'Yes'
                && $data['field']['options'][2] === 'No';

            $data['alias'] = $data['field']['id'];
            $data['isOptional'] = $data['optional'] ?? false;

            if ($isBool) {
                $data['operator'] = 'boolean';
                $data['value'] = (int)$data['eligibilityValue'] === 1 ? 1 : 0;
            } else {
                $data['operator'] = $this->eligibilityOperatorMap()[$operator];

                if ($data['alias'] === 'GPA') {
                    $data['value'] = $this->eligibilityMapGpa($data['eligibilityValue']);
                } else if ($data['field']['id'] === 'gender') {
                    $data['value'] = $this->eligibilityMapGender($data['eligibilityValue']);
                } else if ($data['alias']  === 'militaryAffiliation') {
                    $data['value'] = $this->eligibilityMapMilitaryAffiliation($data['eligibilityValue']);
                } else {
                    $data['value'] = $this->normalizeValue($data['eligibilityValue'] ?? '');
                }
            }

            unset($data['id']);
            unset($data['optional']);
            unset($data['eligibilityType']);
            unset($data['eligibilityValue']);
            unset($data['field']);
        }
    }

    /**
     * Map Sunrise's operators to SOWL's
     *
     * @return array
     */
    public function eligibilityOperatorMap()
    {
        return [
            '' => Eligibility::TYPE_REQUIRED,
            'eq' => Eligibility::TYPE_VALUE,
            'neq' => Eligibility::TYPE_NOT,
            'gt' => Eligibility::TYPE_GREATER_THAN,
            'gte' => Eligibility::TYPE_GREATER_THAN_OR_EQUAL,
            'lt' => Eligibility::TYPE_LESS_THAN,
            'lte' => Eligibility::TYPE_LESS_THAN_OR_EQUAL,
            'in' => Eligibility::TYPE_IN,
            'nin' => Eligibility::TYPE_NIN,
            'between' => Eligibility::TYPE_BETWEEN,
        ];
    }

    /**
     * Map Sunrise's fields to SOWL's
     *
     * @return array
     */
    public function eligibilityFieldsMap()
    {
        return [
            'name' => [Field::FULL_NAME],
            'email' => [Field::EMAIL],
            'phone' => [Field::PHONE],
            'dateOfBirth' => [Field::AGE],
            'state' => [Field::STATE],
            'city' => [Field::CITY],
            'address' => [Field::ADDRESS],
            'zip' => [Field::ZIP],
            'schoolLevel' => [Field::SCHOOL_LEVEL],
            'fieldOfStudy' => [Field::DEGREE],
            'degreeType' => [Field::DEGREE_TYPE],
            'GPA' => [Field::GPA],
            'gender' => [Field::GENDER],
            'ethnicity' => [Field::ETHNICITY],
            'citizenship' => [Field::CITIZENSHIP],
            'enrollmentDate' => [Field::ENROLLMENT_YEAR, Field::ENROLLMENT_MONTH],
            'careerGoal' => [Field::CAREER_GOAL],
            'highSchoolName' => [Field::HIGH_SCHOOL_NAME],
            'highSchoolGraduationDate' => [Field::HIGH_SCHOOL_GRADUATION_YEAR, Field::HIGH_SCHOOL_GRADUATION_MONTH],
            'collegeName' => [Field::COLLEGE_NAME],
            'collegeGraduationDate' => [Field::COLLEGE_GRADUATION_YEAR, Field::COLLEGE_GRADUATION_MONTH],
            'militaryAffiliation' => [Field::MILITARY_AFFILIATION],
            'enrolledInCollege' => [Field::ENROLLED],
        ];
    }

    /**
     * Map SOWL's eligibility fields to Sunrise's
     *
     * @return array
     */
    public function reverseEligibilityFieldsMap()
    {
        return [
            Field::FULL_NAME => 'name',
            Field::EMAIL => 'email',
            Field::PHONE => 'phone',
            Field::AGE => 'dateOfBirth',
            Field::STATE => 'state',
            Field::CITY => 'city',
            Field::ADDRESS => 'address',
            Field::ZIP => 'zip',
            Field::SCHOOL_LEVEL => 'schoolLevel',
            Field::DEGREE => 'fieldOfStudy',
            Field::DEGREE_TYPE => 'degreeType',
            Field::GPA => 'GPA',
            Field::GENDER => 'gender',
            Field::ETHNICITY => 'ethnicity',
            Field::CITIZENSHIP => 'citizenship',
            Field::ENROLLMENT_YEAR => 'enrollmentDate',
            Field::ENROLLMENT_MONTH => 'enrollmentDate',
            Field::CAREER_GOAL => 'careerGoal',
            Field::HIGH_SCHOOL_NAME => 'highSchoolName',
            Field::HIGH_SCHOOL_GRADUATION_YEAR => 'highSchoolGraduationDate',
            Field::HIGH_SCHOOL_GRADUATION_MONTH => 'highSchoolGraduationDate',
            Field::COLLEGE_NAME => 'collegeName',
            Field::COLLEGE_GRADUATION_YEAR => 'collegeGraduationDate',
            Field::COLLEGE_GRADUATION_MONTH => 'collegeGraduationDate',
            Field::MILITARY_AFFILIATION => 'militaryAffiliation',
            Field::ENROLLED => 'enrolledInCollege',
        ];
    }

    public function eligibilityMapGpa($val, $flip = false)
    {
        $map = [
            1 => 'N/A',
            2 => '2.0',
            3 => '2.1',
            4 => '2.2',
            5 => '2.3',
            6 => '2.4',
            7 => '2.5',
            8 => '2.6',
            9 => '2.7',
            10 => '2.8',
            11 => '2.9',
            12 => '3.0',
            13 => '3.1',
            14 => '3.2',
            15 => '3.3',
            16 => '3.4',
            17 => '3.5',
            18 => '3.6',
            19 => '3.7',
            20 => '3.8',
            21 => '3.9',
            22 => '4.0'
        ];

        if ($flip) {
            $map = array_flip($map);
        }

        $result = $this->mapValue($map, $val);

        return $flip ? (int)$result : $result;
    }

    public function eligibilityMapMilitaryAffiliation($val, $flip = false)
    {
        $map = [
            1 => '0',
            2 => '1',
            3 => '2',
            4 => '3',
            5 => '4',
            6 => '5',
            7 => '6',
            8 => '7',
            9 => '8',
            10 => '9',
            11 => '10',
            12 => '11',
            13 => '12',
            14 => '13',
            15 => '14',
            16 => '15',
            17 => '16',
            18 => '17',
            19 => '18',
            20 => '19',
            21 => '20',
            22 => '21',
            23 => '22',
            24 => '23',
            25 => '24',
            26 => '25',
            27 => '26',
            28 => '27',
            29 => '28',
        ];

        if ($flip) {
            $map = array_flip($map);
        }

        $result = $this->mapValue($map, $val);

        return $flip ? (int)$result : $result;
    }

    public function eligibilityMapGender($val, $flip = false)
    {
        $map = [
            1 => 'female',
            2 => 'male',
            3 => 'other',
        ];

        if ($flip) {
            $map = array_flip($map);
        }

        if (!is_numeric($val)) {
            $val = strtolower($val);
        }

        $result = $this->mapValue($map, $val);

        return $flip ? (int)$result : $result;
    }

    public function normalizeValue($val) {
        if (empty($val)) {
            return '';
        } else if (is_array($val)) {
            return implode(',', $val);
        } else {
            return $val;
        }
    }

    private function mapValue($map, $val) {
        if (empty($val) && $val !== 0 && $val !== '0') {
            return '';
        } else if (is_numeric($val)) {
            return $map[$val];
        } else {
            $values = explode(',', $val);
            $resultValues = [];
            foreach ($values as $v) {
                $resultValues[] = $map[$v];
            }

            return implode(',', $resultValues);
        }
    }

    /**
     * Map Sunrise's requirement name ids to SOWL's
     *
     * @return array
     */
    public function requirementNameMap()
    {
        $result = [
            'essay' => 1,
            'transcript' => 17,
            'resume' => 3,
            'recommendation-letter' => 4,
            'cv' => 5,
            'cover-letter' => 6,
            'bio' => 7,
            'video-link' => 8,
            'class-schedule' => 9,
            'proof-of-acceptance' => 10,
            'proof-of-enrollment' => 11,
            'profilepic' => 12,
            'generic-picture' => 13,
            'video-link' => 14,
            'link' => 15,
            'input-text' => 16,
            'survey' => 18,
            'offline-requirement' => 19,
            'checkbox' => 19
        ];

        return $result;
    }

    /**
     * @param Account $account
     * @param int $fieldId
     * @return \DateTime|int|string
     * @throws \Exception
     */
    public function resolveEligibilityField(Account $account, int $fieldId)
    {
        $profile = $account->getProfile();
        $dateOfBirth = $profile->getDateOfBirth();
        $state = $profile->getState();
        $schoolLevel = $profile->getSchoolLevel();
        $degree = $profile->getDegree();
        $degreeType = $profile->getDegreeType();
        $gender = strtolower($profile->getGender());
        $ethnicity = $profile->getEthnicity();
        $citizenship = $profile->getCitizenship();
        $careerGoal = $profile->getCareerGoal();
        $country = $profile->getCountry();
        $militaryAffiliation = $profile->getMilitaryAffiliation();

        switch ($fieldId) {
            case Field::FULL_NAME:
                return $profile->getFullName();
            case Field::EMAIL:
                return $account->getEmail();
            case Field::PHONE:
                return $profile->getPhone();
            case Field::STATE:
                return $state instanceof State ? $state->getId() : null;
            case Field::DATE_OF_BIRTH:
                return $dateOfBirth instanceof \DateTime ? $dateOfBirth->format('Y-m-d') : null;
            case Field::AGE:
                return $dateOfBirth instanceof \DateTime ? $dateOfBirth->format('Y-m-d') : null;
            case Field::COUNTRY:
                return $country instanceof Country ? $country->getId() : null;
            case Field::CITY:
                return $profile->getCity();
            case Field::ADDRESS:
                return $profile->getFullAddress();
            case Field::ZIP:
                return $profile->getZip();
            case Field::SCHOOL_LEVEL:
                return $schoolLevel instanceof SchoolLevel ? $schoolLevel->getId() : null;
            case Field::DEGREE:
                return $degree instanceof Degree ? $degree->getId() : null;
            case Field::DEGREE_TYPE:
                return $degreeType instanceof DegreeType ? $degreeType->getId() : null;
            case Field::GPA:
                return $this->eligibilityMapGpa($profile->getGpa(), true);
            case Field::GENDER:
                return $this->eligibilityMapGender($profile->getGender(), true);
            case Field::ETHNICITY:
                return $ethnicity instanceof Ethnicity ? $ethnicity->getId() : null;
            case Field::CITIZENSHIP:
                return $citizenship instanceof Citizenship ? $citizenship->getId() : null;
            case Field::ENROLLMENT_MONTH:
                return $profile->getEnrollmentMonth() && $profile->getEnrollmentYear() ?
                    sprintf('01-%02d-%d', $profile->getEnrollmentMonth(), $profile->getEnrollmentYear()) : null;
            case Field::ENROLLMENT_YEAR:
                return $profile->getEnrollmentMonth() && $profile->getEnrollmentYear() ?
                    sprintf('01-%02d-%d', $profile->getEnrollmentMonth(), $profile->getEnrollmentYear()) : null;
            case Field::CAREER_GOAL:
                return $careerGoal instanceof CareerGoal ? $careerGoal->getId() : null;
            case Field::HIGH_SCHOOL_GRADUATION_MONTH:
                return $profile->getHighschoolGraduationMonth() && $profile->getHighschoolGraduationYear() ?
                    sprintf('01-%02d-%d', $profile->getHighschoolGraduationMonth(), $profile->getHighschoolGraduationYear()) : null;
            case Field::HIGH_SCHOOL_GRADUATION_YEAR:
                return $profile->getHighschoolGraduationMonth() && $profile->getHighschoolGraduationYear() ?
                    sprintf('01-%02d-%d', $profile->getHighschoolGraduationMonth(), $profile->getHighschoolGraduationYear()) : null;
            case Field::COLLEGE_NAME:
                return $profile->getUniversity();
            case Field::COLLEGE_GRADUATION_MONTH:
                return $profile->getGraduationMonth() && $profile->getGraduationYear() ?
                    sprintf('01-%02d-%d', $profile->getGraduationMonth(), $profile->getGraduationYear()) : null;
            case Field::COLLEGE_GRADUATION_YEAR:
                return $profile->getGraduationMonth() && $profile->getGraduationYear() ?
                    sprintf('01-%02d-%d', $profile->getGraduationMonth(), $profile->getGraduationYear()) : null;
            case Field::HIGH_SCHOOL_NAME:
                return $profile->getHighschool();
            case Field::MILITARY_AFFILIATION:
                return $militaryAffiliation instanceof MilitaryAffiliation ?
                     $this->eligibilityMapMilitaryAffiliation($militaryAffiliation->getId(), true) : null;
            case Field::ENROLLED:
                return $profile->getEnrolled() ? 1 : 2;
            default:
                throw new \Exception("Can not resolve eligibility field [ $fieldId ] to its value");
        }
    }
}
