<?php

/**
 * DaneMediaSubmission
 *
 * @package     ScholarshipOwl\Net\Submissions
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created        09. March 2016.
 * @copyright    Sirio Media
 */

namespace App\Submissions;


use App\Entity\Profile;
use Carbon\Carbon;
use App\Entity\Marketing\Submission;
use Doctrine\ORM\EntityNotFoundException;
use ScholarshipOwl\Data\Service\Marketing\DaneMediaService;

class DaneMediaSubmission extends AbstractSubmission
{
    /*
     *	Submissions Send - Submit stored data
     */
    public function submissionSend($batch)
    {
        $daneMediaService = new DaneMediaService();

        $submissions = $this->ss->getSubmissionsAccountsByNameAndStatus(Submission::NAME_DANE_MEDIA,
            Submission::STATUS_PENDING, $batch);

        /** @var Submission $submission */
        foreach ($submissions as $submission) {
            $submissionId = $submission->getSubmissionId();

            $createdDate = Carbon::instance($submission->getAccount()->getCreatedDate());
            try {
                if ($createdDate->diffInHours(Carbon::now()) > 24) {
                    $this->ss->updateErrorSubmission($submissionId, 'Expired.');
                    continue;
                }

                if (!$submission->getAccount()->isUSA()) {
                    $this->ss->updateErrorSubmission($submissionId, 'Unsupported country.');
                    continue;
                }

                /** @var Profile $profile */
                $profile = $submission->getAccount()->getProfile();

                $params = json_decode($submission->getParams(), true);
                $campaignId = $params["form_id"];

                $campus = $daneMediaService->getDaneMediaCampus($params["campusid"]);

                $data = array(
                    "first_name" => $profile->getFirstName(),
                    "last_name" => $profile->getLastName(),
                    "email" => $submission->getAccount()->getEmail(),
                    "phone1" => $profile->getPhone(),
                    "street1" => $profile->getAddress(),
                    "city" => urlencode($profile->getCity()),
                    "state" => $profile->getState()->getAbbreviation(),
                    "postal_code" => $profile->getZip(),
                    "country" => "US",
                    "ip_address" => $submission->getIpAddress(),
                    "age" => $profile->getAge(),
                    "citizenship" => $this->formatCitizenship($profile->getCitizenship()),
                    "campusname" => $campus->getDisplayValue(),
                    "testflag" => is_production() ? "Live" : "Test"
                );

                if (!is_production()) {
                    $data["xxTest"] = "true";
                }

                if ($militarySubmission = $this->formatMilitaryAffiliation($profile->getMilitaryAffiliation()->getId(),
                    $campaignId)
                ) {
                    $data[$militarySubmission["name"]] = $militarySubmission["value"];
                }

                if ($degreeTypeSubmission = $this->formatDegreeType($profile->getDegreeType()->getId(),
                    $campaignId)
                ) {
                    $data[$degreeTypeSubmission["name"]] = $degreeTypeSubmission["value"];
                }

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

        $noMilitaryAffiliationIds = [428, 475, 527, 636, 638];
        if (in_array($militaryAffiliationId, $noMilitaryAffiliationIds)) {
            return false;
        }

        if (in_array($militaryAffiliationId, $activeIds)) {
            switch ($campaignId) {
                case "718":
                    return ["name" => "militaryaffiliation", "value" => "1"];
                    break;
                case "417":
                    return ["name" => "military_status", "value" => "Active"];
                    break;
                case "674":
                case "678":
                case "680":
                case "682":
                case "684":
                case "686":
                    return ["name" => "militaryaffiliation", "value" => "Active Duty"];
                    break;
                case "720":
                    if ($militaryAffiliationId == 5 || $militaryAffiliationId == 6) {
                        return ["name" => "military_status", "value" => "Guard"];
                    }
                    return ["name" => "military_status", "value" => "Active Duty"];
                    break;
                case "385":
                    return ["name" => "military_status", "value" => "Active Duty"];
                    break;
                case "559":
                case "561":
                case "562":
                case "571":
                case "5061":
                    if ($militaryAffiliationId == 1) {
                        return ["name" => "militaryaffiliation", "value" => "Army"];
                    } else {
                        if ($militaryAffiliationId == 2) {
                            return ["name" => "militaryaffiliation", "value" => "Navy"];
                        } else {
                            if ($militaryAffiliationId == 3) {
                                return ["name" => "militaryaffiliation", "value" => "Air"];
                            } else {
                                if ($militaryAffiliationId == 4) {
                                    return ["name" => "militaryaffiliation", "value" => "Marine"];
                                } else {
                                    if ($militaryAffiliationId == 5) {
                                        return ["name" => "militaryaffiliation", "value" => "Natguard"];
                                    } else {
                                        if ($militaryAffiliationId == 6) {
                                            return ["name" => "militaryaffiliation", "value" => "Coast"];
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
            }
        } else {
            if (in_array($militaryAffiliationId, $veteranIds)) {
                switch ($campaignId) {
                    case "718":
                        return ["name" => "militaryaffiliation", "value" => "1"];
                        break;
                    case "417":
                        return ["name" => "military_status", "value" => "Veteran"];
                        break;
                    case "674":
                    case "678":
                    case "680":
                    case "682":
                    case "684":
                    case "686":
                        if ($militaryAffiliationId == 20) {
                            return ["name" => "militaryaffiliation", "value" => "Retired Military"];
                        }
                        return ["name" => "military_affiliation", "value" => "Veteran"];
                        break;
                    case "720":
                        if ($militaryAffiliationId == 20) {
                            return ["name" => "military_affiliation", "value" => "Retired"];
                        }
                        return ["name" => "military_status", "value" => "Veteran"];
                        break;
                    case "385":
                        return ["name" => "military_status", "value" => "Veteran"];
                        break;
                    case "559":
                    case "561":
                        return ["name" => "militaryaffiliation", "value" => "Veteran"];
                        break;
                }
            } else {
                if (in_array($militaryAffiliationId, $reserveIds)) {
                    switch ($campaignId) {
                        case "718":
                            return ["name" => "militaryaffiliation", "value" => "1"];
                            break;
                        case "417":
                            return ["name" => "military_status", "value" => "Reserve"];
                            break;
                        case "674":
                        case "678":
                        case "680":
                        case "682":
                        case "684":
                        case "686":
                            return ["name" => "militaryaffiliation", "value" => "No military affiliation"];
                            break;
                        case "720":
                            return ["name" => "military_status", "value" => "Reservist"];
                            break;
                        case "385":
                            return ["name" => "military_status", "value" => "Guard/Reserve"];
                            break;
                        case "559":
                        case "561":
                            return ["name" => "militaryaffiliation", "value" => "Reserve"];
                            break;
                    }
                } else {
                    if (in_array($militaryAffiliationId, $dependentIds)) {
                        switch ($campaignId) {
                            case "718":
                                return ["name" => "militaryaffiliation", "value" => "1"];
                                break;
                            case "417":
                                if (in_array($militaryAffiliationId, [9, 12, 15, 18, 21, 24, 27])) {
                                    return ["name" => "military_status", "value" => "Dependent"];
                                }
                                return ["name" => "military_status", "value" => "Spouse"];
                                break;
                            case "674":
                            case "678":
                            case "680":
                            case "682":
                            case "684":
                            case "686":
                                if ($militaryAffiliationId == 20) {
                                    return ["name" => "militaryaffiliation", "value" => "Spouse: Active Member"];
                                }
                                return ["name" => "militaryaffiliation", "value" => "Spouse: Retiree"];
                                break;
                            case "720":
                                return ["name" => "military_status", "value" => "Spouse"];
                                break;
                            case "385":
                                return ["name" => "military_status", "value" => "Spouse"];
                                break;
                            case "559":
                            case "561":
                                return ["name" => "militaryaffiliation", "value" => "Spousdep"];
                                break;
                        }
                    } else {
                        switch ($campaignId) {
                            case "718":
                                return ["name" => "militaryaffiliation", "value" => "0"];
                                break;
                            case "417":
                                return ["name" => "military_status", "value" => "None"];
                                break;
                            case "674":
                            case "678":
                            case "680":
                            case "682":
                            case "684":
                            case "686":
                                return ["name" => "militaryaffiliation", "value" => "No military affiliation"];
                                break;
                            case "720":
                                return ["name" => "military_status", "value" => "Not Affiliated"];
                                break;
                            case "385":
                                return ["name" => "military_status", "value" => "No Military Affiliation"];
                                break;
                            case "559":
                            case "561":
                                return ["name" => "militaryaffiliation", "value" => "Not Applicable"];
                                break;
                        }
                    }
                }
            }
        }

        return false;
    }

    private function formatDegreeType($degreeTypeId, $campaignId)
    {
        if ($campaignId != 529) {
            return false;
        }

        switch ($degreeTypeId) {
            case 2:
            case 5:
                return ["name" => "degree_type", "value" => "CERTIFICATE"];
                break;
            case 3:
                return ["name" => "degree_type", "value" => "ASSOCIATES"];
                break;
            case 4:
                return ["name" => "degree_type", "value" => "BACHELORS"];
                break;
            case 6:
                return ["name" => "degree_type", "value" => "MASTERS"];
                break;
            case 7:
                return ["name" => "degree_type", "value" => "DOCTORAL"];
                break;
            default:
                return ["name" => "degree_type", "value" => "DIPLOMA"];
                break;
        }
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