<?php

namespace App\Submissions;

use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Doctrine\ORM\EntityNotFoundException;

class InboxDollarsSubmission extends AbstractSubmission
{
    public function submissionSend($batch)
    {
        /** @var Submission[] */
        $submissionInboxDollars = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_INBOXDOLLARS,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionInboxDollars as $submission) {
            $submissionId = $submission->getSubmissionId();
            try {

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();
                $state = $profile->getState();
                $data = array(
                    "first_name" => $profile->getFirstName(),
                    "last_name" => $profile->getLastName(),
                    "email" => $submission->getAccount()->getEmail(),
                    "city" => urlencode($profile->getCity()),
                    "state" => $state ? $state->getAbbreviation() : '',
                    "street1" => $profile->getAddress(),
                    "street2" => $profile->getAddress2(),
                    "source_url" => config("app.url"),
                    "zip" => $profile->getZip(),
                    "ip_address" => $submission->getIpAddress(),
                    'phone' => ltrim($profile->getPhoneAreaCode(), "+1").'-'.$profile->getPhonePrefix().'-'.$profile->getPhoneLocal()
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

        \Log::info("SUBMITTED TO INBOX DOLLARS: " . $url);

        $this->curl->post($url);

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
        if (!isset($this->getResponse()->row[0]->success)) {
            $this->errors[] = "Error submitting data.".$this->getResponse()->row[1];
        }
    }
}
