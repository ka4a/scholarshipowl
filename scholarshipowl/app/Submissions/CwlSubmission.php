<?php

/**
 * CwlSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        30. November 2016.
 * @copyright    ScholarshipOwl
 */

namespace App\Submissions;


use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Doctrine\ORM\EntityNotFoundException;

class CwlSubmission extends AbstractSubmission
{

    /*
	 *	Submissions Send - Submit stored data
	 */
    public function submissionSend($batch)
    {
        $submissions = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_CWL,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                $data = array(
                    "attendeeType" => "Student",
                    "emailAddress" => $submission->getAccount()->getEmail(),
                    "firstName" => $profile->getFirstName(),
                    "lastName" => $profile->getLastName(),
                    "nationality" => "US",
                    "phoneNumber" => $profile->getPhone(),
                    "state" => $profile->getState()->getAbbreviation(),
                );

                $this->send($data);

                $response = $this->getResponse();

                if ($this->hasErrors()) {
                    \CoregLogger::error($this->getErrors());
                    $this->ss->updateErrorSubmission($submissionId, $this->getErrors()[0]);
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

        $settings = is_production() ? "CwlProduction" : "CwlStaging";

        $config = \Config::get("scholarshipowl.submission." . $settings);

        $this->curl->setOpt(CURLOPT_HTTPHEADER, array(
            "X-PQ-API-Key: 9fc36c694b3a7a9f7c68af7d64ca4848",
            'Content-Type:application/json'
        ));

        $this->curl->post($config["url"], $params);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

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
        if ($this->curl->httpStatusCode != 201) {
            $this->errors[] = $this->response[0]->message;
        }
    }
}
