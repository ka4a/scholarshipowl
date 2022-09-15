<?php

/**
 * AcademixSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        18. January 2016.
 * @copyright    Sirio Media
 */

namespace App\Submissions;


use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Doctrine\ORM\EntityNotFoundException;

class AcademixSubmission extends AbstractSubmission
{
    /*
	 *	Submissions Send - Submit stored data
	 */
    public function submissionSend($batch)
    {
        /** @var Submission[] $submissionsAcademix */
        $submissionsAcademix = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_ACADEMIX,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionsAcademix as $submission) {
            $submissionId = $submission->getSubmissionId();
            try {

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                $data = array(
                    "FirstName" => $profile->getFirstName(),
                    "LastName" => $profile->getLastName(),
                    "Email" => $submission->getAccount()->getEmail(),
                    "Phone" => $profile->getPhone(),
                    "City" => urlencode($profile->getCity()),
                    "State" => $profile->getState()->getAbbreviation(),
                    "Zip" => $profile->getZip(),
                    "IPAddress" => $submission->getIpAddress()
                );

                if ($submission->getParams()) {
                    $data = array_merge($data, json_decode($submission->getParams(), true));
                }

                $this->send($data);

                $response = $this->getRawResponse();

                if ($this->hasErrors()) {
                    \CoregLogger::error($this->getErrors());
                    $this->ss->updateErrorSubmission($submissionId, $response);
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

        $url = $this->getUrl() . "?" . http_build_query($params);

        \Log::info("SUBMITTED TO ACADEMIX: " . $url);

        $this->curl->post($url);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->curlErrorMessage, $this->curl->curlErrorCode);
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
        if ($this->getResponse()->status != "Success") {
            $this->errors[] = "Error submitting data.";
        }
    }
}
