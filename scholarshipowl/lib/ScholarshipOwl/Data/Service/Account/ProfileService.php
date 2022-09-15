<?php

/**
 * ProfileService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created        07. October 2014.
 * @copyright    Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\Profile;
use ScholarshipOwl\Data\Entity\Info\Country;
use ScholarshipOwl\Data\Entity\Info\Field;
use ScholarshipOwl\Data\Entity\Scholarship\Eligibility;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\AbstractService;


class ProfileService extends AbstractService implements IProfileService
{
    private $fields = array(
        "basic" => array(
            "first_name",
            "last_name",
            "phone",
            "date_of_birth",
            "gender",
            "citizenship_id",
            "ethnicity_id",
            "is_subscribed",
            "military_affiliation_id",
            "profile_type",
            "agree_call"
        ),
        "education" => array(
            "school_level_id",
            "degree_id",
            "degree_type_id",
            "gpa",
            "enrollment_month",
            "enrollment_year",
            "highschool",
            'highschool_address1',
            'highschool_address2',
            "highschool_graduation_month",
            "highschool_graduation_year",
            "enrolled",
            "university",
            'university_address1',
            'university_address2',
            "university1",
            "university2",
            "university3",
            "university4",
            "graduation_month",
            "graduation_year",
            "study_country1",
            "study_country2",
            "study_country3",
            "study_country4",
            "study_country5",
        ),
        "interests" => array(
            "career_goal_id",
            "study_online"
        ),
        "location" => array(
            "country_id",
            "state_id",
            "state_name",
            "city",
            "address",
            "address2",
            "zip"
        )
    );

    public function getProfile($accountId)
    {
        return $this->getProfileFiltered($accountId);
    }

    public function getBasicProfile($accountId)
    {
        return $this->getProfileFiltered($accountId, "basic");
    }

    public function getEducationProfile($accountId)
    {
        return $this->getProfileFiltered($accountId, "education");
    }

    public function getInterestsProfile($accountId)
    {
        return $this->getProfileFiltered($accountId, "interests");
    }

    public function getLocationProfile($accountId)
    {
        return $this->getProfileFiltered($accountId, "location");
    }

    public function setBasicProfile(Profile $profile)
    {
        return $this->setProfileFiltered($profile, "basic");
    }

    public function setEducationProfile(Profile $profile)
    {
        return $this->setProfileFiltered($profile, "education");
    }

    public function setInterestsProfile(Profile $profile)
    {
        return $this->setProfileFiltered($profile, "interests");
    }

    public function setLocationProfile(Profile $profile)
    {
        return $this->setProfileFiltered($profile, "location");
    }

    public function isEligible(Profile $profile, Scholarship $scholarship)
    {
        $result = false;

        $fieldsOk = true; // true for now
        $eligibilitiesOk = false;

        $eligibilities = $scholarship->getEligibilities();
        foreach ($eligibilities as $eligibility) {
            $fieldId = $eligibility->getField()->getFieldId();
        }

        return $result;
    }

    private function getProfileFiltered($accountId, $field = null)
    {
        $result = null;
        $columns = array();

        if (empty($field)) {
            $columns = array("*");
        } else {
            $columns = $this->fields[$field];
        }

        $data = $this->getByColumn(self::TABLE_PROFILE, "account_id", $accountId, $columns);
        if (!empty($data)) {
            $result = new Profile();
            $result->populate($data);
        }

        return $result;
    }

    private function setProfileFiltered(Profile $profile, $field)
    {
        $result = null;
        $data = $this->extractArray($profile->toArray(), $this->fields[$field]);

        $nullables = array(
            "citizenship_id",
            "ethnicity_id",
            "school_level_id",
            "degree_id",
            "degree_type_id",
            "career_goal_id",
            "state_id",
            "state_name",
            "university1",
            "university2",
            "university3",
            "university4",
            "profile_type",
            "agree_call",
            "study_country1",
            "study_country2",
            "study_country3",
            "study_country4",
            "study_country5",
        );


        if (isset($data['university']) && is_array($data['university'])) {
            $universities = $data['university'];
            if (isset($universities[0])) {
                if (is_numeric($universities[0])) {
                    /** @var \App\Entity\College $college */
                    $college = \EntityManager::find(\App\Entity\College::class, $universities[0]);
                    if (isset($college)) {
                        $data['university'] = $college->getCanonicalName();
                    }
                } else {
                    $data['university'] = $universities[0];
                }
            }


            if (isset($universities[1])) {
                if (is_numeric($universities[1])) {
                    /** @var \App\Entity\College $college */
                    $college = \EntityManager::find(\App\Entity\College::class, $universities[1]);
                    if (isset($college)) {
                        $data['university1'] = $college->getCanonicalName();
                    }
                } else {
                    $data['university1'] = $universities[1];
                }
            }

            if (isset($universities[2])) {
                if (is_numeric($universities[2])) {
                    /** @var \App\Entity\College $college */
                    $college = \EntityManager::find(\App\Entity\College::class, $universities[2]);
                    if (isset($college)) {
                        $data['university2'] = $college->getCanonicalName();
                    }
                } else {
                    $data['university2'] = $universities[2];
                }
            }

            if (isset($universities[3])) {
                if (is_numeric($universities[3])) {
                    /** @var \App\Entity\College $college */
                    $college = \EntityManager::find(\App\Entity\College::class, $universities[3]);
                    if (isset($college)) {
                        $data['university3'] = $college->getCanonicalName();
                    }
                } else {
                    $data['university3'] = $universities[3];
                }
            }
        }

        foreach ($nullables as $nullable) {
            /** Match any study_country1, study_country2, .... */
            if (strpos('study_country', $nullable) && strlen($nullable) === 14 && $data[$nullable] instanceof Country) {
                $data[$nullable] = $data[$nullable]->getCountryId();
            }
            if (array_key_exists($nullable, $data) && empty($data[$nullable])) {
                $data[$nullable] = null;
            }
        }

        $result = $this->update(self::TABLE_PROFILE, $data, array("account_id" => $profile->getAccountId()));
        $this->execute(
            sprintf("UPDATE %s SET last_updated_date = ? WHERE account_id = ?", self::TABLE_ACCOUNT),
            array(date("Y-m-d H:i:s"), $profile->getAccountId())
        );

        return $result;
    }

    private function extractArray($array, $keys)
    {
        $result = array();

        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                $result[$key] = $array[$key];
            }
        }

        return $result;
    }
}
