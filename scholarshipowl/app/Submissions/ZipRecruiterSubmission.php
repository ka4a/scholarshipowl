<?php

namespace App\Submissions;

use App\Entity\Account;
use App\Entity\Profile;
use App\Services\Marketing\CoregService;
use App\Entity\Marketing\Submission;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;

class ZipRecruiterSubmission extends AbstractSubmission
{
    /**
     * @var SubmissionService
     */
    protected $ss;
    public function submissionSend($batch)
    {
        $submissionsZipRecruiter = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_ZIPRECRUITER,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionsZipRecruiter as $submission) {
            $submissionId = $submission->getSubmissionId();

            /** @var Account $account */
            $account = $submission->getAccount();
            try {
                if (!$account->isUSA()) {
                    $this->ss->updateErrorValidationSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $account->getProfile();

                $data = array(
                    "email" => $account->getEmail(),
                    "name" => $profile->getFirstName() . " " . $profile->getLastName(),
                    "search" => $profile->getCareerGoal()->getName(),
                    "location" => $profile->getZip(),
                    "ip_address" => $submission->getIpAddress(),
                );

                $this->send($data);

                $response = $this->getResponse();

                if ($this->hasErrors()) {
                    \CoregLogger::error($this->getErrors());
                    $error = implode(',', $this->getErrors());
                    $response = json_encode($response);
                    $this->ss->updateErrorSubmittionSubmission($submissionId, $error.$response);
                } else {
                    $this->ss->updateSuccessSubmission($submissionId, json_encode($response));
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

    public function onRequest($params = array())
    {
        $this->curl->setHeader("Content-Type", "application/json");
        $this->curl->setOpt(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOpt(CURLOPT_USERPWD, $this->auth["username"] . ":" . $this->auth["password"]);
        $this->curl->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $result = array();

        foreach ($params as $name => $value) {
            $result[$name] = $value;
        }

        return $result;
    }

    public function onResponse()
    {
    }
}
