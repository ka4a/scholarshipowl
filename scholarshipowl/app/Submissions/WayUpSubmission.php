<?php

/**
 * WayUpSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        06. Sep 2016.
 * @copyright    ScholarshipOwl
 */

namespace App\Submissions;

use App\Entity\College;
use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use App\Services\Marketing\SubmissionService;
use Carbon\Carbon;
use Doctrine\ORM\EntityNotFoundException;

class WayUpSubmission extends AbstractSubmission
{
    /**
     * @var SubmissionService
     */
    protected $ss;

    /*
	 *	Submissions Send - Submit stored data
	 */
    public function submissionSend($batch)
    {
        $submissions = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_WAY_UP,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorValidationSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                $dateOfBirth = Carbon::instance($profile->getDateOfBirth());

                if (($college = $this->getCollegeInfo($profile)) && $profile->getGraduationYear() && $profile->getGraduationMonth()) {
                    $data = array(
                        "email" => $submission->getAccount()->getEmail(),
                        "first_name" => $profile->getFirstName(),
                        "last_name" => $profile->getLastName(),
                        "phone_number" => $profile->getPhone(),
                        "current_student" => (boolean)$profile->getEnrolled(),
                        "date_of_birth" => $dateOfBirth->toDateString(),
                        "ethnicity" => $this->formatEthnicity($profile->getEthnicity()->getName()),
                        "veteran_status" => $this->formatMilitaryAffiliation($profile->getMilitaryAffiliation()->getId()),
                        "campus_IPEDS_id" => $college["iped"],
                        "graduation_year" => (int)$profile->getGraduationYear(),
                        "graduation_month" => (int)$profile->getGraduationMonth(),
                        "gpa" => floatval($profile->getGpa()),
                        "expected_degree" => $this->formatDegreeType($profile->getDegreeType()->getName()),
                        "current_address" => $profile->getAddress(),
                        "current_city" => urlencode($profile->getCity()),
                        "current_state" => $profile->getState()->getAbbreviation(),
                        "current_zip" => $profile->getZip(),
                        "current_country" => "US"
                    );

                    $this->send($data);

                    $response = $this->getResponse();

                    if ($this->hasErrors()) {
                        \CoregLogger::error($this->getErrors());
                        $error = implode(',', $this->getErrors());
                        if($response != ''){
                            $error = $error.' '.$response;
                        }
                        $this->ss->updateErrorSubmittionSubmission($submissionId, $error);
                    } else {
                        $this->ss->updateSuccessSubmission($submissionId, $response);
                    }
                } else {
                    $this->ss->updateErrorValidationSubmission($submissionId,
                        "No eligible college or graduation date found");
                }
            }
            catch (EntityNotFoundException $notFound){
                \Log::info("Account not found while sending submission [$submissionId]");
                $this->ss->updateErrorSubmission($submissionId, 'Account was deleted');
            }
            catch (\Throwable $exc) {
                \CoregLogger::error($exc);
                $this->ss->updateErrorValidationSubmission($submissionId, $exc->getMessage());
            }
        }
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $params = $this->onRequest($params);

        $url = $this->getUrl();
        $data_string = json_encode($params);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                "x-partner-token: OdAvOKQFwBc52QHpPtdiSNK4Rell7g",
                'Content-Type:application/json'
            )
        );

        $response = curl_exec($ch);
        curl_close($ch);

        $this->response = $response;
        $this->rawResponse = $response;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->curlErrorMessage, $this->curl->curlErrorCode);
        }

        $this->onResponse();
    }

    public function onRequest($params = array())
    {
        $result = $this->getAuth();

        foreach ($params as $name => $value) {
            $result[$name] = $value;
        }

        return $result;
    }

    public function onResponse()
    {
        $response = json_decode($this->getResponse());

        if ($response->error == true) {
            $this->errors[] = $this->getResponse();
        }
    }

    private function formatEthnicity($ethnicityName)
    {
        if (!$ethnicityName) {
            return "undisclosed";
        }
        switch ($ethnicityName) {
            case "Caucasian":
                return "white";
            case "African American":
                return "black";
            case "Hispanic / Latino":
                return "hispanic";
            case "Asian / Pacific Islander":
                return "asian";
            case "American Indian / Native Alaskan":
                return "indian";
            case "Other":
                return "undisclosed";
        }

        return "undisclosed";
    }

    private function formatMilitaryAffiliation($militaryAffiliationId)
    {
        $veteranIds = [14, 17, 20, 23, 26];

        $noMilitaryAffiliationIds = [0, 428, 475, 527, 636, 638];
        if (in_array($militaryAffiliationId, $noMilitaryAffiliationIds)) {
            return "no";
        } else {
            if (in_array($militaryAffiliationId, $veteranIds)) {
                return "veteran";
            }
        }

        return "current";
    }

    /**
     * @param Profile $profile
     * @return array|bool
     */
    private function getCollegeInfo($profile)
    {
        $result = false;

        $userUniversities = $profile->getUniversities();

        foreach ($userUniversities as $university){
            $res = $this->checkUsersColleges($university);
            if(is_null($res)){
                break;
            }

            $result = $res;
        }

        return $result;
    }

    private function formatDegreeType($degreeType)
    {
        switch ($degreeType) {
            case "Undecided":
                return "Other degree";
            case "Associate's Degree":
                return "Associate's";
            case "Bachelor's Degree":
                return "Bachelor's";
            case "Master's Degree":
                return "Master's";
            case "Doctoral (Ph.D.)":
                return "Ph.D";
            default:
                return "Other degree";
        }
    }

    /**
     * @param $university
     *
     * @return null
     */
    protected function checkUsersColleges($university)
    {
        $result = null;

        $college = \DB::connection()
            ->table('college')
            ->where("iped_code", "!=", "")
            ->where("canonical_name", "like", "%$university%")
            ->limit(1)
            ->get();
        
        if($college->count() > 0){
            $result["iped"] = $college[0]->iped_code;
            $result["name"] = $college[0]->colloquial_name;
        }

        return $result;
    }
}
