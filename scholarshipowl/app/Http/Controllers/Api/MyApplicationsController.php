<?php

namespace App\Http\Controllers\Api;

use App\Entity\Application;
use App\Events\Account\ApplicationsRemoveEvent;
use App\Events\Account\ApplicationsSubmitEvent;
use ScholarshipOwl\Data\Entity\Scholarship\ApplicationStatus;
use ScholarshipOwl\Data\Entity\Scholarship\ApplicationEssayStatus;
use ScholarshipOwl\Data\Service\Scholarship\ApplicationService;
use ScholarshipOwl\Data\Service\Scholarship\ScholarshipService;
use ScholarshipOwl\Http\JsonModel;


/**
 * MyApplications Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */
class MyApplicationsController extends BaseController
{

    /**
     * MyApplications Index Action - Gets Applied Scholarships (GET)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function indexAction()
    {
        $model = $this->getOkModel("my-applications");
        $data = array("scholarships" => array());

        try {
            $applicationService = new ApplicationService();
            $scholarshipService = new ScholarshipService();

            $account = $this->getLoggedUser();
            $applications = $applicationService->getApplicationsByStatuses($account->getAccountId(), [
                ApplicationStatus::ERROR,
                ApplicationStatus::IN_PROGRESS,
                ApplicationStatus::PENDING,
                ApplicationStatus::SUCCESS
            ]);


            if (!empty($applications)) {
                // Get Both Active & Inactive Scholarships, Expired From 1st April
                $scholarships = $scholarshipService->getScholarshipsData(array_keys($applications), false,
                    "2015-04-01");
                $essayIds = $applicationService->getApplicationsEssaysIds($account->getAccountId());
                $applicationEssayStatuses = ApplicationEssayStatus::getApplicationEssayStatuses();

                foreach ($scholarships as $scholarship) {
                    $row = array(
                        "scholarship_id" => $scholarship["scholarship_id"],
                        "description" => $scholarship["description"],
                        "url" => $scholarship["url"],
                        "terms_of_service_url" => $scholarship["terms_of_service_url"],
                        "title" => $scholarship["title"],
                        "expiration_date" => date("m/d/Y", strtotime($scholarship["expiration_date"])),
                        "amount" => number_format($scholarship["amount"]),
                        "is_recurrent" => boolval($scholarship["is_recurrent"]),
                    );

                    $data["scholarships"][$scholarship["scholarship_id"]] = $row;
                }
            }

            $model->setData($data);
        } catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }


    /**
     * Put MyApplications Index Action - Updates Applied Scholarships (PUT)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function putIndexAction()
    {
        $model = $this->getOkModel("my-account");
        $data = array("scholarships" => array(), "incomplete" => array());

        try {
            $input = $this->getAllInput();

            $applicationService = new ApplicationService();
            $scholarshipService = new ScholarshipService();

            $account = $this->getLoggedUser();


            if (!empty($input["scholarships"])) {
                // Filter What Can Be Submited
                $filteredIds = array();
                $incomplete = array();

                $scholarshipIds = $input["scholarships"];

                $applicationsEssayIds = $applicationService->getApplicationsEssaysIds($account->getAccountId());
                $scholarships = $scholarshipService->getScholarshipsData($scholarshipIds);

                foreach ($scholarships as $scholarshipId => $scholarship) {
                    $applicationsEssayIds = array_merge($applicationsEssayIds,
                        $applicationService->getApplicationEssayIdsFromFiles($account->getAccountId(), $scholarshipId));
                    $essayIds = array_keys($scholarship["essays"]);
                    $ready = true;

                    foreach ($essayIds as $essayId) {
                        if (!in_array($essayId, $applicationsEssayIds)) {
                            $ready = false;
                            break;
                        }
                    }

                    if ($ready == true) {
                        $filteredIds[] = $scholarshipId;
                    } else {
                        $incomplete[] = $scholarshipId;
                    }
                }

                if (!empty($filteredIds)) {
                    $applicationService->submitScholarships($account->getAccountId(), $filteredIds);
                    $data["scholarships"] = $filteredIds;


                    // Fire Event
                    \Event::dispatch(new ApplicationsSubmitEvent($account->getAccountId()));
                }

                if (!empty($incomplete)) {
                    $data["incomplete"] = $incomplete;
                }
            }

            $model->setData($data);
        } catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }


    /**
     * Delete MyApplications Index Action - Removes Applied Scholarships (DELETE)
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function deleteIndexAction()
    {
        $model = $this->getOkModel("my-applications");
        $data = array("scholarships" => array());

        try {
            $input = $this->getAllInput();

            $applicationService = new ApplicationService();
            $scholarshipService = new ScholarshipService();

            $account = $this->getLoggedUser();
            $subscription = $this->getLoggedUserSubscription();


            if (!empty($input["scholarships"])) {
                // Get Both Active & Inactive Scholarships
                $scholarships = $scholarshipService->getScholarshipsData($input["scholarships"], false);

                if (!empty($scholarships)) {
                    $applications = $applicationService->getApplications($account->getAccountId());
                    $scholarshipIds = array();

                    // Only These Statuses Can Be Deleted
                    $allowedStatuses = array(ApplicationStatus::PENDING, ApplicationStatus::NEED_MORE_INFO);

                    // Filter What Can Be Deleted
                    foreach ($scholarships as $scholarshipId => $scholarship) {
                        if (array_key_exists($scholarshipId, $applications)) {
                            $applicationStatusId = $applications[$scholarshipId];
                            if (in_array($applicationStatusId, $allowedStatuses)) {
                                $scholarshipIds[] = $scholarshipId;
                            }
                        }
                    }

                    if (!empty($scholarshipIds)) {
                        $applicationService->undoApplyScholarships($account->getAccountId(), $scholarshipIds);
                        $data["scholarships"] = $scholarshipIds;

                        // Fire Event
                        \Event::dispatch(new ApplicationsRemoveEvent($account->getAccountId()));
                    }
                }
            }

            $model->setData($data);
        } catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }
}
