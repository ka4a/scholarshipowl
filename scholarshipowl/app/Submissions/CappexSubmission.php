<?php

/**
 * CappexSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        04. May 2016.
 * @copyright    Sirio Media
 */

namespace App\Submissions;


use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use App\Services\Marketing\SubmissionService;
use Carbon\Carbon;
use Doctrine\ORM\EntityNotFoundException;
use Illuminate\Http\Response;
use Throwable;

class CappexSubmission extends AbstractSubmission
{

    /**
     * @var SubmissionService
     */
    protected $ss;

    public function submissionSend($batch)
    {
        /** @var Submission[] $submissionsCappex */
        $submissionsCappex = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_CAPPEX,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionsCappex as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                $createdDate = Carbon::instance($submission->getAccount()->getCreatedDate());

                if ($createdDate->diffInHours(Carbon::now()) > 24) {
                    $this->ss->updateErrorValidationSubmission($submissionId, 'Expired.');
                    continue;
                }

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorValidationSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                if ($colleges = $this->getCappexCollegeIds($profile)) {
                    if ($this->stringBetween($profile->getFirstName(), 2,
                            35) && $this->stringBetween($profile->getLastName(), 2, 35)
                    ) {
                        $data = array(
                            "emailAddress" => $submission->getAccount()->getEmail(),
                            "generatePassword" => true,
                            "firstName" => $profile->getFirstName(),
                            "lastName" => $profile->getLastName(),
                            "gender" => strtoupper($profile->getGender()[0]),
                            "address1" => $profile->getAddress(),
                            "city" => $profile->getCity(),
                            "state" => $profile->getState()->getAbbreviation(),
                            "zipCode" => $profile->getZip(),
                            "birthYear" => $profile->getDateOfBirthYear(),
                            "birthMonth" => $profile->getDateOfBirthMonth(),
                            "birthDay" => $profile->getDateOfBirthDay(),
                            "highSchoolGradMonth" => $profile->getHighschoolGraduationMonth(),
                            "highSchoolGradYear" => $profile->getHighschoolGraduationYear(),
                            "highSchoolGpaUnweighted" => $profile->getGpa(),
                            "collegeMajorIds" => $this->getMajorIds($profile),
                            "consideringCollegeIds" => $colleges,
                        );

                        if ($submission->getParams()) {
                            $data = array_merge($data, json_decode($submission->getParams(), true));
                        }


                        $this->send($data);

                        $response = $this->getRawResponse();

                        if ($this->hasErrors()) {
                            \CoregLogger::error($this->getErrors());

                            $error = implode(',', $this->getErrors());
                            if($response != ''){
                                $error = $error.' '.$response;
                            }

                            $this->ss->updateErrorSubmittionSubmission($submissionId, $error);
                        } else {
                            $plugin = $this->cs->getCoregPluginByName(Submission::NAME_CAPPEX);
                            $this->cs->updateCoregPluginAllocation($plugin);
                            $this->ss->updateSuccessSubmission($submissionId, $response);
                        }
                    } else {
                        $this->ss->updateErrorValidationSubmission($submissionId, "Name validation failed");
                    }
                } else {
                    $this->ss->updateErrorValidationSubmission($submissionId, "No sufficient prospect colleges");
                }
            }
            catch (EntityNotFoundException $notFound){
                \Log::info("Account not found while sending submission [$submissionId]");
                $this->ss->updateErrorSubmission($submissionId, 'Account was deleted');
            }
            catch (Throwable $ex) {
                \CoregLogger::error($ex);
                $this->ss->updateErrorSubmission($submissionId, $ex->getMessage());
            }
        }
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $params = $this->onRequest($params);

        $url = $this->getUrl() . "?" . http_build_query($params);

        \Log::info("SUBMITTED TO CAPPEX: " . $url);

        $this->curl->post($url);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->rawResponse.' '.$this->curl->errorCode.' '.$this->curl->errorMessage);
        }

        return $this->onResponse();
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
        if (empty($this->getResponse()) && $this->curl->httpStatusCode == Response::HTTP_FOUND) {
            $this->errors[] = "Http code: 302, ". $this->curl->rawResponseHeaders;
        }
    }

    public function getCappexCollegeIds(Profile $profile)
    {
        $result = [];

        $userUniversities = $profile->getUniversities();
        $collegeCount = 0;
        foreach ($userUniversities as $university){
            $res = $this->checkUsersColleges($university);
            if(is_null($res)){
                break;
            }
            $collegeCount++;
            $result[] = $res;
        }

        if ($collegeCount < 3) {
            return false;
        }
        $result = implode(',', $result);

        return $result;

    }

    public function getMajorIds(Profile $profile)
    {
        switch ($profile->getDegree()->getId()) {
            case 1:
                return "258,259";
            case 2:
                return "19,1451";
            case 3:
                return "322,323,1456";
            case 4:
                return "40,52,374";
            case 5:
                return "216,401,697,75";
            case 6:
                return "48,475,1460,77,880";
            case 7:
                return "82,106,490,491";
            case 8:
                return "515,159";
            case 9:
                return "101,602";
            case 10:
                return "108,116,651,653";
            case 11:
                return "23,657,658";
            case 12:
                return "684,685,1757";
            case 13:
                return "28,710";
            case 14:
                return "97,148,768,769";
            case 15:
                return "276,807,808";
            case 16:
                return "285,861,901,904";
            case 17:
                return "39,909";
            case 18:
                return "911,1511";
            case 19:
                return "311,312,938,940,1523";
            case 20:
                return "163,112,944,163";
            case 21:
                return "171,1571";
            case 22:
                return "1572,999";
            case 23:
                return "1017,1018,1449";
            case 24:
                return "1090,1091,1092,1582,1585";
            case 25:
                return "538,1116,1470";
            case 26:
                return "1588,1132";
            case 27:
                return "371,1143,132";
            case 28:
                return "1177,1178";
            case 29:
                return "1601,457,620,145,1199";
            case 30:
                return "1202,207";
            case 31:
                return "1269,1621,1267,1268,1610";
            case 32:
                return "1289,158";
            case 33:
                return "1347";
            case 34:
                return "1356,38";
            case 35:
                return "167,263,759,65,1643,1372";
            case 36:
                return "1644,1422,1423";
            default:
                return "";
        }
    }

    protected function stringBetween($string, $min, $max)
    {
        $l = mb_strlen($string);
        return ($l >= $min && $l <= $max);
    }

    /**
     * @param $university
     *
     * @return null|int
     */
    protected function checkUsersColleges($university)
    {
        $cappexCollegeId = null;

        $college = \DB::connection()
            ->table('college')
            ->where("iped_code", "!=", "")
            ->where('canonical_name', 'LIKE', "%$university%")
            ->limit(1)
            ->get();

        if($college->count() > 0){
            $ipedCode = $college[0]->iped_code;
            $cappexCollage = \DB::connection()
                ->table('cappex_college')
                ->select('cappex_college_id')
                ->where("cappex_college_id", "=", $ipedCode)
                ->limit(1)
                ->get();

            if($cappexCollage->count() > 0){
                $cappexCollegeId = $cappexCollage[0]->cappex_college_id;
            }
        }

        return $cappexCollegeId;
    }
}
