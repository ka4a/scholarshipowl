<?php

/**
 * CollegeExpressSubmission
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
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityNotFoundException;

class CollegeExpressSubmission extends AbstractSubmission
{
    /**
     * @var SubmissionService
     */
    protected $ss;

    public function submissionSend($batch)
    {
        /** @var Submission[] $submissionsOpinionOutpost */
        $submissionsCollegeExpress = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_COLLEGE_EXPRESS,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionsCollegeExpress as $submission) {
            $submissionId = $submission->getSubmissionId();

            try {
                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorValidationSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                $data = array(
                    "site_id" => 10144,
                    "is_test" => is_production() ? 0 : 1,
                    "email" => $submission->getAccount()->getEmail(),
                    "first_name" => $profile->getFirstName(),
                    "last_name" => $profile->getLastName(),
                    "address1" => $profile->getAddress(),
                    "address2" => $profile->getAddress2(),
                    "city" => $profile->getCity(),
                    "state_province" => $profile->getState()->getAbbreviation(),
                    "postal_code" => $profile->getZip(),
                    "dob" => $profile->getDateOfBirth()->format('m/d/Y'),
                    "hs_grad_year" => $profile->getHighschoolGraduationYear(),
                    "hs_gpa" => $profile->getGpa(),
                    "gender" => strtoupper($profile->getGender()[0]),
                    "ip" => $submission->getIpAddress(),
                );

                if ($submission->getParams()) {
                    $data = array_merge($data, json_decode($submission->getParams(), true));
                }

                $this->curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
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
        $result = json_decode($this->getRawResponse());

        if ($result->result != "New") {
            $this->errors = [$result->result];
        }
    }
}
