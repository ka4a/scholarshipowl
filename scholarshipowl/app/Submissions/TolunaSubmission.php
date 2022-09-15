<?php

/**
 * TolunaSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created        23. July 2015.
 * @copyright    Sirio Media
 */

namespace App\Submissions;

use App\Entity\Profile;
use App\Services\Marketing\CoregService;
use Carbon\Carbon;
use \Curl\Curl;
use App\Entity\Marketing\Submission;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;

class TolunaSubmission extends AbstractSubmission
{
    public function submissionSend($batch)
    {
        /** @var Submission[] $submissionsToluna */
        $submissionsToluna = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_TOLUNA,
            Submission::STATUS_PENDING, $batch);

        /**
         * @var Submission $submission
         */
        foreach ($submissionsToluna as $submission) {
            $submissionId = $submission->getSubmissionId();
            try {
                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                $genderCode = 0;
                if ($profile->getGender() == 'female') {
                    $genderCode = '2000246';
                } else {
                    if ($profile->getGender() == 'male') {
                        $genderCode = '2000247';
                    }
                }

                $schoolLevel = 0;
                $schoolLevelId = $profile->getSchoolLevel()->getId();
                if ($schoolLevelId <= 6) {
                    $schoolLevel = '2002270';
                } else {
                    if ($schoolLevelId > 6 && $schoolLevelId < 8) {
                        $schoolLevel = '2002272';
                    } else {
                        if ($schoolLevelId == 9) {
                            $schoolLevel = '2002271';
                        }
                    }
                }

                $dateOfBirth = Carbon::instance($profile->getDateOfBirth());

                $data = array(
                    "SourceId" => '50007636',
                    "email" => $submission->getAccount()->getEmail(),
                    "FirstName" => $profile->getFirstName(),
                    "LastName" => $profile->getLastName(),
                    "CountryID" => "2000223", // Us
                    "Language" => "2000240", // English
                    "Gender" => $genderCode,
                    "Employment" => "2796317", // studenr
                    "ZipCode" => $profile->getZip(),
                    "Education" => $schoolLevel,
                    "DOB" => $dateOfBirth->toDateString(),
                );

                \Log::info("Sending toluna data for " . $submission->getAccount()->getEmail());
                $this->send($data);

                $response = $this->getResponse();

                if ($this->hasErrors()) {
                    \CoregLogger::error($this->getErrors());
                    $this->ss->updateErrorSubmission($submissionId, $response->result);
                } else {
                    $this->ss->updateSuccessSubmission($submissionId, $response);
                }
            }
            catch (EntityNotFoundException $notFound){
                \Log::info("Account not found while sending submission [$submissionId]");
                $this->ss->updateErrorSubmission($submissionId, 'Account was deleted');
            }
            catch (\Throwable $exc) {
                \CoregLogger::error($exc);
                $this->ss->updateErrorSubmission($submissionId, $exc->getMessage());
            }
        }
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();


        $params = $this->onRequest($params);
        $params = json_encode($params);

        $this->curl->setOpt(CURLINFO_CONTENT_LENGTH_UPLOAD, strlen($params));
        $this->curl->post($this->getUrl(), $params);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            $this->errors[] = $this->getResponse();
        }
        return;
    }


    public function onRequest($params = array())
    {
        $this->curl->setHeader("Content-Type", "application/json");

        $result = $this->getAuth();

        foreach ($params as $name => $value) {
            $result[$name] = $value;
        }

        return $result;
    }

    public function onResponse()
    {
        return;
    }

}
