<?php

/**
 * ZuUsaSubmission Class
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        18. July 2016.
 * @copyright    ScholarshipOwl
 */

namespace App\Submissions;

use App\Entity\Marketing\Submission;
use App\Entity\Profile;
use Carbon\Carbon;
use Doctrine\ORM\EntityNotFoundException;
use ScholarshipOwl\Data\Service\Marketing\ZuUsaService;

class ZuUsaSubmission extends AbstractSubmission
{
    /*
     *	Submissions Send - Submit stored data
     */
    public function submissionSend($batch)
    {
        $zuUsaService = new ZuUsaService();

        $submissions = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_ZU_USA,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $submissionId = $submission->getSubmissionId();
            try {
                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                $createdDate = Carbon::instance($submission->getAccount()->getCreatedDate());

                if ($createdDate->diffInHours(Carbon::now()) > 24) {
                    $this->ss->updateErrorSubmission($submissionId, 'Expired.');
                    continue;
                }

                $skipSubmission = false;

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                $params = json_decode($submission->getParams(), true);
                $campaignId = $params["form_id"];
                $campus = isset($params["campus"]) ? $params["campus"] : "";

                $campaign = $zuUsaService->getZuUsaCampaign($campaignId);
                $campaignSettings = \Config::get("zuusa." . $campaignId);

                $data = array(
                    "first_name" => $profile->getFirstName(),
                    "last_name" => $profile->getLastName(),
                    "email" => $submission->getAccount()->getEmail(),
                    "phone1" => ltrim($profile->getPhone(), "+1"),
                    "street1" => $profile->getAddress(),
                    "city" => urlencode($profile->getCity()),
                    "state" => $profile->getState()->getAbbreviation(),
                    "postal_code" => $profile->getZip(),
                    "ip_address" => $submission->getIpAddress(),
                    "age" => $profile->getAge(),
                    "citizenship" => $this->formatCitizenship($profile->getCitizenship()),
                    "country" => "US",
                    "custom_enrolled" => $profile->getEnrolled() ? "Yes" : "No",
                    "custom_source" => 2,
                    "custom_gender" => ($campaignId == 35 || $campaignId == 36 || $campaignId == 37 || $campaignId == 38) ? ucfirst($profile->getGender())[0] : ucfirst($profile->getGender()),
                    "dob" => \DateTime::createFromFormat('Y-m-d h:i:s', $profile->getDateOfBirth())->format('Y-m-d'),
                );

                if (!is_production()) {
                    $data["testflag"] = "Test";
                    $data["xxTest"] = "true";
                } else {
                    $data["testflag"] = "Live";
                }

                if ($militarySubmission = $this->formatMilitaryAffiliation($profile->getMilitaryAffiliation()->getId(),
                    $campaignId)
                ) {
                    $data[$militarySubmission["name"]] = $militarySubmission["value"];
                }

                if ($campaignId == 12 && ($profile->getAge() < 18 || $profile->getEnrolled())) {
                    $this->ss->updateErrorSubmission($submissionId, "Lead not submitted");
                    $skipSubmission = true;
                }

                if ($params = $submission->getParams()) {
                    $params = str_replace("program_id", "programid", $params);
                    $params = str_replace("campus", "campusid", $params);
                    $params = str_replace("yearHSGED", "hsgradyr", $params);
                    $params = str_replace("educationCompleted", "edulevelid", $params);
                    $params = str_replace("universal_leadid", "uleadid", $params);

                    $params = str_replace("\"phone2\":\"\"", "\"phone2\":\"" . ltrim($profile->getPhone(), "+1") . "\"",
                        $params);

                    $data = array_merge($data, json_decode($params, true));
                }

                if ($campaignId == 22 || $campaignId == 23) {
                    $data["custom_call_center_sourced"] = 0;
                    $data["custom_job_supplier"] = "Not Job Traffic";
                    $data["custom_is_job_sourced"] = 0;
                }

                if ($campaignId == 35 || $campaignId == 36 || $campaignId == 37 || $campaignId == 38) {
                    $data["custom_tcpa_text"] = substr($campaignSettings["consent"], 0, 252) . "...";
                }

                if (!$skipSubmission) {
                    $this->setUrl($campaign->getSubmissionUrl());
                    $this->send($data);

                    $response = $this->getRawResponse();

                    if ($this->hasErrors()) {
                        \CoregLogger::error($this->getErrors());
                        $this->ss->updateErrorSubmission($submissionId, $response);
                    } else {
                        $zuUsaService->updateCampusCapping($campus);
                        $this->ss->updateSuccessSubmission($submissionId, $response);
                    }
                }
            }
            catch (EntityNotFoundException $notFound){
                \Log::info("Account not found while sending submission [$submissionId]");
                $this->ss->updateErrorSubmission($submissionId, 'Account was deleted');
            }
            catch (\Throwable $exc) {
                \CoregLogger::error($exc->getMessage());
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
        if (is_object($this->getResponse())) {
            if (strtolower($this->getResponse()->result) != "success") {
                $this->errors[] = "Error submitting data.";
            }
        } else {
            if (strpos(strtolower($this->getResponse()), "success") === false) {
                $this->errors[] = "Error submitting data.";
            }
        }
    }

    private function formatMilitaryAffiliation($militaryAffiliationId, $campaignId)
    {
        $activeIds = [1, 2, 3, 4, 5, 6, 7, 8];
        $veteranIds = [14, 17, 20, 23, 26];
        $dependentIds = [9, 10, 12, 13, 15, 16, 18, 19, 21, 22, 24, 25, 27, 28];
        $reserveIds = [11];

        $noMilitaryAffiliationIds = [0, 428, 475, 527, 636, 638];
        if (in_array($militaryAffiliationId, $noMilitaryAffiliationIds)) {
            switch ($campaignId) {
                case 12:
                    return array("name" => "militaryaffiliation", "value" => "None");
                    break;
                case 14:
                    return array("name" => "militaryaffiliation", "value" => "No Military Affiliation");
                    break;
                case 22:
                case 23:
                    return array("name" => "militaryaffiliation", "value" => "None");
                    break;
                case 3:
                case 24:
                case 25:
                    return array("name" => "militaryaffiliation", "value" => "No Military Affiliation");
                    break;
                case 30:
                    return array("name" => "militaryaffiliation", "value" => "No");
                    break;
                case 33:
                    return array("name" => "militaryaffiliation", "value" => "No Affiliation");
                    break;
                case 35:
                case 36:
                case 37:
                case 38:
                    return array("name" => "militaryaffiliation", "value" => "No U.S. Military Affiliation");
                    break;
            }
        } else {
            if (in_array($militaryAffiliationId, $reserveIds)) {
                switch ($campaignId) {
                    case 12:
                    case 14:
                        return array("name" => "militaryaffiliation", "value" => "Reserve");
                        break;
                    case 22:
                    case 23:
                    case 33:
                        return array("name" => "militaryaffiliation", "value" => "Reserve");
                        break;
                    case 3:
                    case 24:
                    case 25:
                        return array("name" => "militaryaffiliation", "value" => "No Military Affiliation");
                        break;
                    case 30:
                        return array("name" => "militaryaffiliation", "value" => "Yes");
                        break;
                    case 35:
                    case 36:
                    case 37:
                    case 38:
                        return array("name" => "militaryaffiliation", "value" => "No U.S. Military Affiliation");
                        break;
                }
            } else {
                switch ($campaignId) {
                    case 35:
                    case 36:
                    case 37:
                    case 38:
                        if (in_array($militaryAffiliationId, $dependentIds)) {
                            return array("name" => "militaryaffiliation", "value" => "No U.S. Military Affiliation");
                        }
                        if ($militaryAffiliationId == 1) {
                            return ["name" => "militaryaffiliation", "value" => "U.S. Army"];
                        } else {
                            if ($militaryAffiliationId == 2) {
                                return ["name" => "militaryaffiliation", "value" => "U.S. Navy"];
                            } else {
                                if ($militaryAffiliationId == 3) {
                                    return ["name" => "militaryaffiliation", "value" => "U.S. Air Force"];
                                } else {
                                    if ($militaryAffiliationId == 4) {
                                        return ["name" => "militaryaffiliation", "value" => "U.S. Marine Corps"];
                                    } else {
                                        if ($militaryAffiliationId == 5) {
                                            return ["name" => "militaryaffiliation", "value" => "U.S. Army"];
                                        } else {
                                            if ($militaryAffiliationId == 6) {
                                                return ["name" => "militaryaffiliation", "value" => "U.S. Coast Guard"];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        break;
                    case 12:
                        if (in_array($militaryAffiliationId, $dependentIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Dependent");
                        }
                        if (in_array($militaryAffiliationId, $activeIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Active");
                        }
                        if (in_array($militaryAffiliationId, $veteranIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Veteran");
                        }
                        break;
                    case 14:
                        if (in_array($militaryAffiliationId, $dependentIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Spouse: Active Member");
                        }
                        if (in_array($militaryAffiliationId, $activeIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Active Duty");
                        }
                        if (in_array($militaryAffiliationId, $veteranIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Veteran");
                        }
                        break;
                    case 22:
                    case 23:
                        if (in_array($militaryAffiliationId, $dependentIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Dependent");
                        }
                        if (in_array($militaryAffiliationId, $activeIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Active");
                        }
                        if (in_array($militaryAffiliationId, $veteranIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Veteran");
                        }
                        break;
                    case 3:
                    case 24:
                    case 25:
                        if (in_array($militaryAffiliationId, $dependentIds)) {
                            return array("name" => "militaryaffiliation", "value" => "No Military Affiliation");
                        }
                        if (in_array($militaryAffiliationId, $activeIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Active Duty");
                        }
                        if (in_array($militaryAffiliationId, $veteranIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Veteran");
                        }
                        break;
                    case 30:
                        return array("name" => "militaryaffiliation", "value" => "Yes");
                        break;
                    case 33:
                        if (in_array($militaryAffiliationId, $dependentIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Military Dependent");
                        }
                        if (in_array($militaryAffiliationId, $activeIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Active Duty");
                        }
                        if (in_array($militaryAffiliationId, $veteranIds)) {
                            return array("name" => "militaryaffiliation", "value" => "Veteran");
                        }
                        break;
                }
            }
        }
        return array("name" => "military", "value" => 0);
    }

    public function formatCitizenship($citizenship)
    {
        switch ($citizenship->getId()) {
            case 1:
                return "US Citizen";
            case 2:
                return "Permanent Resident";
            case 3:
                return "Other";
        }
        return false;
    }
}