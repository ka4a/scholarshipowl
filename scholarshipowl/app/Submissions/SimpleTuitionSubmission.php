<?php

namespace App\Submissions;

use App\Entity\Account;
use App\Entity\Profile;
use App\Services\Marketing\CoregService;
use App\Entity\Marketing\Submission;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;

class SimpleTuitionSubmission extends AbstractSubmission
{

    const HS_SENIOR_YEAR = 2017;

    /**
     * @var SubmissionService
     */
    protected $ss;

    public function submissionSend()
    {
        $submissionsSimpleTuition = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_SIMPLE_TUITION,
            Submission::STATUS_PENDING);

        /** @var Submission $submission */
        foreach ($submissionsSimpleTuition as $submission) {
            $submissionId = $submission->getSubmissionId();

            /** @var Account $account */
            $account = $submission->getAccount();

            try{
                if (!$account->isUSA()) {
                    $this->ss->updateErrorValidationSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $account->getProfile();

                $data = array(
                    "Email" => $account->getEmail(),
                    "First Name" => $profile->getFirstName(),
                    "Last Name" => $profile->getLastName(),
                    "Graduation Date" => $profile->getGraduationYear() . "-" . $profile->getGraduationMonth(),
                    "School Name" => $profile->getUniversity(),
                );

                if ($profile->getSchoolLevel()->getId() == 4) {
                    if ($profile->getGraduationYear() == self::HS_SENIOR_YEAR) {
                        $data["Intended College"] = $profile->getUniversity();
                    } else {
                        $this->ss->updateErrorValidationSubmission($submissionId, 'Lead not matching.');
                        continue;
                    }
                }

                $this->send($data);

                $response = $this->getResponse();

                if ($this->hasErrors()) {
                    $error = implode(',', $this->getErrors());
                    if($response != ''){
                        $error = $error.' '.$response;
                    }
                    \CoregLogger::error($error);
                    $this->ss->updateErrorSubmittionSubmission($submissionId, $error);
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
                $this->ss->updateErrorSubmittionSubmission($submissionId, $exc->getMessage());
            }
        }
    }

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $params = json_encode($params);

        $this->onRequest($params);

        $this->curl->setOpt(CURLINFO_CONTENT_LENGTH_UPLOAD, strlen($params));
        $this->curl->post($this->getUrl(), $params);

        $this->response = $this->curl->response;
        $this->rawResponse = $this->curl->rawResponse;

        if ($this->curl->error) {
            $this->errors[] = $this->curl->response;
        }
        return;
    }

    public function onRequest($params = array())
    {
        $this->curl->setHeader("Content-Type", "application/json");
        $this->curl->setOpt(CURLOPT_URL, $this->getUrl());
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOpt(CURLOPT_USERPWD, $this->auth["username"] . ":" . $this->auth["password"]);
        $this->curl->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    }

    public function onResponse()
    {
        return;
    }
}
