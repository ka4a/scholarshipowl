<?php

namespace App\Submissions;

use App\Entity\Account;
use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Doctrine\ORM\EntityNotFoundException;

class ISaySubmission extends AbstractSubmission
{

    protected $genderMap = [
        'male' => 1,
        'female' => 2
    ];

    public function submissionSend($batch)
    {
        /** @var Submission[] */
        $submissionISay = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_ISAY,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionISay as $submission) {
            $submissionId = $submission->getSubmissionId();
            try {

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /**
                 * @var Account $account
                 */
                $account = $submission->getAccount();
                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();
                $data = array(
                    "fname" => $profile->getFirstName(),
                    "lname" => $profile->getLastName(),
                    "email" => $submission->getAccount()->getEmail(),
                    "dob" => $profile->getDateOfBirthMonth() . '/' . $profile->getDateOfBirthYear(),
                    "gender" => $this->genderMap[$profile->getGender()],
                    "agreement" => 1,
                    "locale" => 'en-us',
                    "vid" => $account->getAccountId(),
                    "ip" => $submission->getIpAddress(),
                );

                if ($submission->getParams()) {
                    $data = array_merge($data, json_decode($submission->getParams(), true));
                }

                $this->send($data);

                $response = $this->getRawResponse();

                if ($this->hasErrors()) {
                    \CoregLogger::error($this->getErrors());

                    if($this->getResponse()->status->__toString() == ' denied ' ){
                        $this->ss->updateErrorValidationSubmission($submissionId, $response);
                    }

                } else {
                    $this->ss->updateSuccessSubmission($submissionId, $response);
                }
            }
            catch (EntityNotFoundException $notFound){
                \Log::info("Account not found while sending submission [$submissionId]");
                $this->ss->updateErrorSubmittionSubmission($submissionId, 'Account was deleted');
            }
            catch (\Throwable $exc) {
                \CoregLogger::error($exc);
                $this->ss->updateErrorSubmittionSubmission($submissionId, $exc->getMessage());
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

        \Log::info("SUBMITTED TO I-SAY: " . $url);

        $this->curl->get($url);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->rawResponse);
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
        if (isset($this->getResponse()->status) && (string)$this->getResponse()->status != " success ") {
            $this->errors[] = "Error submitting data.".(string)$this->getResponse()->reason;
        }
    }
}
