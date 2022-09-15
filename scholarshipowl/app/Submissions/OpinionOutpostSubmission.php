<?php

/**
 * OpinionOutpostSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        19. May 2015.
 * @copyright    Sirio Media
 */

namespace App\Submissions;

use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Doctrine\ORM\EntityNotFoundException;

class OpinionOutpostSubmission extends AbstractSubmission
{
    public function submissionSend($batch)
    {
        /** @var Submission[] $submissionsOpinionOutpost */
        $submissionsOpinionOutpost = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_OPINION_OUTPOST,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionsOpinionOutpost as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                $data = array(
                    "email" => $submission->getAccount()->getEmail(),
                    "fn" => $profile->getFirstName(),
                    "ln" => $profile->getLastName(),
                    "gender" => $this->getOpinionOutpostGenderId($profile->getGender()),
                    "returnStatus" => "ok|error",
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
        if (strpos(strtolower($this->getRawResponse()), "error") !== false) {
            $this->errors[] = "Error";
        }
    }

    public function getOpinionOutpostGenderId($gender)
    {
        if ($gender == "male") {
            return 1;
        }
        return 2;
    }
}
