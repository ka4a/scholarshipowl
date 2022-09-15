<?php
/**
 * Author: Ivan Krkotic <ivan@siriomedia.com>
 * Date: 11/03/16
 */

namespace App\Submissions;

use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Carbon\Carbon;
use Doctrine\ORM\EntityNotFoundException;

class BerecruitedSubmission extends AbstractSubmission
{
    /*
     *	Submissions Send - Submit stored data
     */
    public function submissionSend($batch)
    {
        /** @var Submission[] $submissionsBerecruited */
        $submissionsBerecruited = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_BERECRUITED,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissionsBerecruited as $submission) {
            $submissionId = $submission->getSubmissionId();
            try {
                $createdDate = Carbon::instance($submission->getAccount()->getCreatedDate());

                if ($createdDate->diffInHours(Carbon::now()) > 24) {
                    $this->ss->updateErrorSubmission($submissionId, 'Expired.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                $city = $profile->getCity();
                $state = $profile->getState();
                $contry = $profile->getCountry();

                $bday = null;
                if(!is_null($profile->getDateOfBirth())) {
                    $bday = $profile->getDateOfBirth()->format('Y-m-d');
                }

                $data = array(
                    "overall_gpa" => $profile->getGpa(),
                    "home_addr1" => $profile->getFullAddress(),
                    "home_addr_city" => $city ? $city : '',
                    "home_addr_state" => $state ? $state->getAbbreviation() : '',
                    "birth_date" => $bday,
                    "country_code" => $contry ? $contry->getAbbreviation(): '',
                    "athlete_first_name" => $profile->getFirstName(),
                    "athlete_last_name" => $profile->getLastName(),
                    "athlete_email" => $submission->getAccount()->getEmail(),
                    "athlete_phone" => $profile->getPhone(),
                    "zip" => $profile->getZip(),
                    "event_id" => "14782",
                    "process_now" => "no",
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

    public function send($params = null)
    {
        $this->response = "";
        $this->rawResponse = "";
        $this->errors = array();

        $params = $this->onRequest($params);

        $url = $this->getUrl();
        $data_string = json_encode(array("recruit" => $params));
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $response = curl_exec($ch);
        curl_close($ch);

        $this->response = $response;
        $this->rawResponse = $response;

        if ($this->curl->error) {
            throw new \RuntimeException($this->curl->curlErrorMessage, $this->curl->curlErrorCode);
        }

        $this->onResponse();
    }

    public function onResponse()
    {
        $response = json_decode($this->getResponse());

        if (is_object($response)) {
            if (property_exists($response, "error")) {
                $this->errors[] = "Error submitting data.";
            }
        } else {
            if (strpos(strtolower($this->getResponse()), "error") !== false) {
                $this->errors[] = "Error submitting data.";
            }
        }
    }
}