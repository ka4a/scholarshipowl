<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Country;
use App\Entity\Domain;
use App\Entity\Marketing\Submission;
use App\Entity\Repository\AccountRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\State;
use App\Entity\ScholarshipStatus;
use App\Services\Marketing\SubmissionService;
use Doctrine\ORM\EntityManager;
use App\Entity\Account;
use ScholarshipOwl\Data\Entity\Account\Referral;
use ScholarshipOwl\Data\Entity\Payment\Transaction;
use ScholarshipOwl\Data\Service\Account\ConversationService;
use ScholarshipOwl\Data\Service\Account\LoginHistoryService;
use ScholarshipOwl\Data\Service\Account\ReferralService;
use ScholarshipOwl\Data\Service\Account\ReferralShareService;
use ScholarshipOwl\Data\Service\Account\SearchService as AccountSearchService;
use ScholarshipOwl\Data\Service\Marketing\AffiliateService;
use ScholarshipOwl\Data\Service\Marketing\EdumaxService;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;
use ScholarshipOwl\Data\Service\Mission\SearchService as MissionSearchService;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;
use ScholarshipOwl\Data\Service\Payment\StatisticService as PaymentStatisticService;
use ScholarshipOwl\Data\Service\Payment\TransactionService;
use ScholarshipOwl\Data\Service\Scholarship\ScholarshipService;
use ScholarshipOwl\Data\Service\Scholarship\SearchService as ScholarshipSearchService;
use ScholarshipOwl\Data\Service\Statistic\StatisticDailyService;


/**
 * Export Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */
class ExportController extends BaseController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SubmissionService
     */
    protected $ss;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarships;

    /**
     * ExportController constructor.
     *
     * @param EntityManager     $em
     * @param SubmissionService $ss
     */
    public function __construct(EntityManager $em, SubmissionService $ss)
    {
        parent::__construct();

        $this->em = $em;
        $this->ss = $ss;
        $this->scholarships = $em->getRepository(Scholarship::class);
    }

    /**
     * Accounts Export Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function accountsAction()
    {
        $result = "";

        $fileName = "Accounts_" . date("Y-m-d H:i:s") . ".csv";
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename='$fileName'"
        );

        try {
            set_time_limit(3600);
            ini_set("memory_limit", "512M");

            $input = $this->getAllInput();
            $domains = Domain::options();
            $csvHeaders = array();
            $csvData = array();

            $accountSearchService = new AccountSearchService();
            $applicationService = new ApplicationService();
            $paymentStatisticService = new PaymentStatisticService();
            $scholarshipService = new ScholarshipService();
            $loginHistoryService = new LoginHistoryService();
            $conversationService = new ConversationService();

            $searchResult = $accountSearchService->searchAccounts($input, "");
            $accountIds = array_keys($searchResult['data']);
            $accounts = \EntityManager::getRepository(\App\Entity\Account::class)
                ->findBy(['accountId' => $accountIds], ['accountId' => 'DESC']);

            $applicationsAmount = $applicationService->getApplicationsAmount($accountIds);
            $subscriptions = $paymentStatisticService->getTopPrioritySubscriptions($accountIds);
            $loginHistory = $loginHistoryService->getLastLoginDate($accountIds);
            $conversations = $conversationService->getLastConversation($accountIds);

            $eligibilities = array();
            $eligibilitiesAmount = array();
            $allScholarships = array();

            foreach ($accounts as $accountId => $account) {
                $scholarshipIds = $this->scholarships->findEligibleNotAppliedScholarshipsIds($accountId);
                $eligibilities[$accountId] = $scholarshipIds;

                foreach ($scholarshipIds as $scholarshipId) {
                    $allScholarships[$scholarshipId] = true;
                }
            }

            $allScholarships = $scholarshipService->getScholarshipsInfo(array_keys($allScholarships));
            foreach ($accounts as $accountId => $account) {
                $sum = 0;
                $scholarshipIds = $eligibilities[$accountId];

                foreach ($scholarshipIds as $scholarshipId) {
                    $sum += $allScholarships[$scholarshipId]->getAmount();
                }

                $eligibilitiesAmount[$accountId] = $sum;
            }


            $csvHeaders = array(
                "Account ID",
                "Domain",
                "Account Status",
                "Account Type",
                "Email",
                "Username",
                "Password",
                "Created Date",
                "Last Updated Date",
                "First Name",
                "Last Name",
                "Phone",
                "Consent to be Called",
                "Date Of Birth",
                "Gender",
                "Citizenship",
                "Ethnicity",
                "Mail Subscription",
                "Country",
                "State",
                "City",
                "Address",
                "Zip",
                "School Level",
                "Degree",
                "Degree Type",
                "Enrollment Year",
                "Enrollment Month",
                "GPA",
                "Career Goal",
                "University Graduation Year",
                "University Graduation Month",
                "Highschool Graduation Year",
                "Highschool Graduation Month",
                "Study Online",
                "University",
                "HighSchool",
                "Profile Completeness",
                "Paid",
                "Package",
                "Price",
                "Eligible Scholarships Count",
                "Eligible Scholarships Amount",
                "Applications Count",
                "Applications Amount",
                "Last Login",
                "Last Conversation Date",
                "Last Conversation Status",
            );

            /**
             * @var int $accountId
             * @var Account $account
             */
            foreach ($accounts as $accountId => $account) {
                $profile = $account->getProfile();
                $applicationCount = count($account->getApplications());
                $paid = "No";
                $package = "";
                $price = "";
                $appliedAmount = "";
                $lastLogin = "";
                $lastConversationDate = "";
                $lastConversationStatus = "";

                if (array_key_exists($accountId, $subscriptions)) {
                    $subscription = $subscriptions[$accountId];

                    if ($subscription->isPaid()) {
                        $paid = "Yes";
                    }

                    $package = $subscriptions[$accountId]->getName();
                    $price = $subscriptions[$accountId]->getPrice();
                }

                if (array_key_exists($accountId, $applicationsAmount)) {
                    $appliedAmount = $applicationsAmount[$accountId];
                }

                if (array_key_exists($accountId, $loginHistory)) {
                    $lastLogin = $loginHistory[$accountId];
                }

                if (array_key_exists($accountId, $conversations)) {
                    $lastConversationDate = $conversations[$accountId]->getLastConversationDate();
                    $lastConversationStatus = $conversations[$accountId]->getStatus();
                }

                $csvData[] = array(
                    $account->getAccountId(),
                    $domains[$account->getDomain()->getId()],
                    $account->getAccountStatus()->__toString(),
                    $account->getAccountType()->__toString(),
                    $account->getEmail(),
                    $account->getUsername(),
                    $account->getPassword(),
                    $account->getCreatedDate()->format('Y-m-d h:i:s'),
                    $account->getLastUpdatedDate() && $account->getLastUpdatedDate()->format('Y') !== '-0001'
                        ? $account->getLastUpdatedDate()->format('Y-m-d h:i:s') : '',
                    $profile->getFirstName(),
                    $profile->getLastName(),
                    $profile->getPhone(),
                    $profile->getAgreeCall() ? "Yes" : "No",
                    $profile->getDateOfBirth() ? $profile->getDateOfBirth()->format('Y-m-d') : ''       ,
                    ucfirst($profile->getGender()),
                    $profile->getCitizenship() ? $profile->getCitizenship()->getName() : '',
                    $profile->getEthnicity() ? $profile->getEthnicity()->getName() : '',
                    $profile->getIsSubscribed() == "1" ? "Yes" : "No",
                    $profile->getCountry() ? $profile->getCountry()->getName() : '',
                    $profile->getState() ? $profile->getState()->getName() : '',
                    $profile->getCity() ?? '',
                    $profile->getAddress() ?? '',
                    $profile->getZip() ?? '',
                    $profile->getSchoolLevel() ? $profile->getSchoolLevel()->getName() : '',
                    $profile->getDegree() ? $profile->getDegree()->getName() : '',
                    $profile->getDegreeType() ? $profile->getDegreeType()->getName() : '',
                    $profile->getEnrollmentYear() ?? '',
                    $profile->getEnrollmentMonth() ?? '',
                    $profile->getGpa() ?? '',
                    $profile->getCareerGoal() ? $profile->getCareerGoal()->getName() : '',
                    $profile->getGraduationYear() ?? '',
                    $profile->getGraduationMonth() ?? '',
                    $profile->getHighschoolGraduationYear() ?? '',
                    $profile->getHighschoolGraduationMonth() ?? '',
                    $profile->getStudyOnline() ? ucfirst($profile->getStudyOnline()) : '',
                    $profile->getUniversity() ?? '',
                    $profile->getHighschool() ?? '',
                    $profile->getCompleteness(),
                    $paid,
                    $package,
                    $price,
                    count($eligibilities[$accountId]),
                    $eligibilitiesAmount[$accountId],
                    $applicationCount,
                    $appliedAmount,
                    $lastLogin,
                    $lastConversationDate,
                    $lastConversationStatus
                );
            }

            $result .= $this->getCSVLine($csvHeaders);
            foreach ($csvData as $row) {
                $result .= $this->getCSVLine($row);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $this->response($result, $headers, 200);
    }


    /**
     * Applications Export Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function applicationsAction()
    {
        try {
            $fileName = "Applications_" . date("Y-m-d H:i:s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;

            $input = $this->getAllInput();
            $service = new ScholarshipSearchService();
            $resultSet = $service->searchApplications($input, "", true);
            $statuses = \ScholarshipOwl\Data\Entity\Scholarship\ApplicationStatus::getApplicationStatuses();

            $handle = fopen($file, "w+");
            fwrite($handle, $this->getCSVLine(array(
                "Account ID",
                "Full Name",
                "Email",
                "Phone",
                "Scholarship ID",
                "Scholarship",
                "Status",
                "Type",
                "Date Applied",
                "Universities",
            )));

            /**
             * @var Application[] $resultSet
             */
            foreach ($resultSet as $row) {
                $account = $row->getAccount();
                $profile = $account->getProfile();

                $data = $this->getCSVLine(array(
                    $account->getAccountId(),
                    $profile->getFullName(),
                    $account->getEmail(),
                    $account->getProfile()->getPhone(),
                    $row->getScholarship()->getScholarshipId(),
                    $row->getScholarship()->getTitle(),
                    $row->getApplicationStatus()->getName(),
                    ucfirst($row->getScholarship()->getApplicationType()),
                    format_date($row->getDateApplied()->format('Y-m-d h:m:s'), true),
                    $profile->getUniversity1().', '.$profile->getUniversity2().', '.$profile->getUniversity3().', '.$profile->getUniversity4()
                ));

                fwrite($handle, $data);
            }

            fclose($handle);


            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($file));
            header("Cache-Control: must-revalidate");
            header("Expires: 0");
            header("Pragma: public");

            readfile($file);
            unlink($file);

            exit;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    public function scholarshipsPublicAction()
    {
        $fileName = 'Scholarships_public_' . date('Y-m-d H:i:s') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}"
        ];

        $input = $this->getAllInput();
        $csvData = [];

        $searchService = new ScholarshipSearchService();

        $searchResult = $searchService->searchScholarships($input, '', true);
        $scholarships = $searchResult['data'];

        $csvHeaders = array(
            'Title',
            'Description',
            'Amount',
            'Url',
            'Expiration Date',
        );

        $result = $this->getCSVLine($csvHeaders);

        /** @var \ScholarshipOwl\Data\Entity\Scholarship\Scholarship $scholarship */
        foreach ($scholarships as $scholarship) {
            $csvData[] = [
                $scholarship->getTitle(),
                $scholarship->getDescription(),
                $scholarship->getAmount(),
                $scholarship->getPublicUrl(),
                $scholarship->getExpirationDate(),
            ];
        }

        foreach ($csvData as $row) {
            $result .= $this->getCSVLine($row);
        }

        return $this->response($result, $headers, 200);
    }

    /**
     * Scholarships Export Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function scholarshipsAction()
    {
        $result = "";

        $fileName = "Scholarships_" . date("Y-m-d H:i:s") . ".csv";
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$fileName}"
        );

        try {
            $input = $this->getAllInput();
            $csvData = array();

            $searchService = new ScholarshipSearchService();

            $searchResult = $searchService->searchScholarships($input, "", true);
            $scholarships = $searchResult["data"];
            $scholarshipsIds = array();

            foreach ($scholarships as $scholarship) {
                $scholarshipsIds[] = $scholarship->getScholarshipId();
            }

            $csvHeaders = array(
                "Scholarship ID",
                "Title",
                "Url",
                "Expiration Date",
                "Amount",
                "Up To",
                "Awards",
                "Description",
                "Application Type",
                "Apply Url",
                "Email",
                "Email Subject",
                "Email Message",
                "Form Action",
                "Form Method",
                "Terms Of Service Url",
                "Privacy Police Url",
                "Active",
                "Status",
                "Free",
                "Created Date",
                "Last Updated Date"
            );

            /** @var \ScholarshipOwl\Data\Entity\Scholarship\Scholarship $scholarship */
            foreach ($scholarships as $scholarship) {

                $csvData[] = array(
                    $scholarship->getScholarshipId(),
                    $scholarship->getTitle(),
                    $scholarship->getUrl(),
                    $scholarship->getExpirationDate(),
                    $scholarship->getAmount(),
                    $scholarship->getUpTo(),
                    $scholarship->getAwards(),
                    $scholarship->getDescription(),
                    $scholarship->getApplicationType(),
                    $scholarship->getApplyUrl(),
                    $scholarship->getEmail(),
                    $scholarship->getEmailSubject(),
                    $scholarship->getEmailMessage(),
                    $scholarship->getFormAction(),
                    $scholarship->getFormMethod(),
                    $scholarship->getTermsOfServiceUrl(),
                    $scholarship->getPrivacyPolicyUrl(),
                    $scholarship->isActive() == "1" ? "Yes" : "No",
                    ScholarshipStatus::find($scholarship->getStatus())->getName(),
                    $scholarship->isFree() == "1" ? "Yes" : "No",
                    $scholarship->getCreatedDate(),
                    $scholarship->getLastUpdatedDate()
                );
            }

            $result .= $this->getCSVLine($csvHeaders);
            foreach ($csvData as $row) {
                $result .= $this->getCSVLine($row);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $this->response($result, $headers, 200);
    }


    /**
     * Transactions Export Action
     */
    public function transactionsAction()
    {
        $service = new TransactionService();
        $searchResult = $service->searchTransactions($this->getAllInput(), "");
        $transactions = $searchResult["data"];

        $result = $this->getCSVLine([
            "Full Name",
            'Domain',
            "Amount",
            "Status",
            "Date",
            "Expiration Type",
            "Payment Method",
            "Credit Card",
            "Device",
            "HasOffers Affiliate ID",
            "HasOffers Transaction ID",
            "Package Name",
            "Payment Number",
            "Consent to be called",
            "Phone Number"
        ]);

        /** @var Transaction $transaction */
        foreach ($transactions as $transaction) {
            $result .= $this->getCSVLine([
                $transaction->getAccount()->getProfile()->getFullName(),
                Domain::find($transaction->getAccount()->getDomain()->getId())->getName(),
                $transaction->getAmount(),
                $transaction->getTransactionStatus()->getName(),
                $transaction->getCreatedDate(),
                $transaction->getSubscription()->getExpirationType(),
                $transaction->getPaymentMethod()->getName(),
                $transaction->getCreditCardType(),
                $transaction->getDevice(),
                $transaction->has_offers_affiliate_id,
                $transaction->has_offers_transaction_id,
                $transaction->getSubscription()->getName(),
                $transaction->getRecurrentNumber(),
                $transaction->getAccount()->getProfile()->getAgreeCall() ? "Yes" : "No",
                $transaction->getAccount()->getProfile()->getPhone(),
            ]);
        }

        return response($result, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=transactions_'.date('Y-m-d H:i:s').'.csv',
        ]);
    }

    /**
     * Transactions Export Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function dailyManagementAction()
    {
        $result = "";

        $fileName = "DailyManagement_" . date("Y-m-d H:i:s") . ".csv";
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename='$fileName'"
        );

        try {
            $input = $this->getAllInput();
            $csvHeaders = array();
            $csvData = array();

            $service = new StatisticDailyService();
            $searchResult = $service->search($input, "");
            $statistics = $searchResult["data"];
            $csvHeaders = array("Date");
            foreach (reset($statistics) as $date => $statistic) {
                $csvHeaders[] = $statistic->getStatisticDailyType();
            }

            foreach ($statistics as $date => $statisticData) {
                $csvData[$date][] = $date;
                foreach ($statisticData as $statistic) {
                    $csvData[$date][] = $statistic->getValue();
                }
            }

            $result .= $this->getCSVLine($csvHeaders);
            foreach ($csvData as $row) {
                $result .= $this->getCSVLine($row);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $this->response($result, $headers, 200);
    }


    /**
     * Marketing System Export Action
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function marketingSystemAction()
    {
        $result = "";

        $fileName = "MarketingSystem_" . date("Y-m-d H:i:s") . ".csv";
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename='$fileName'"
        );

        try {
            $input = $this->getAllInput();
            $csvHeaders = array();
            $csvData = array();

            $service = new MarketingSystemService();
            $searchResult = $service->search($input, "");
            $data = $searchResult["data"];

            $csvHeaders = array(
                "Account ID",
                "Full Name",
                "Marketing System",
                "Transaction ID",
                "Offer ID",
                "Affiliate ID",
                "Conversion Date"
            );
            foreach ($data as $accountId => $account) {
                $csvData[] = array(
                    $accountId,
                    $account->getAccount()->getProfile()->getFullName(),
                    $account->getMarketingSystem(),
                    $account->getHasOffersTransactionId(),
                    $account->getHasOffersOfferId(),
                    $account->getHasOffersAffiliateId(),
                    $account->getConversionDate(),
                );
            }

            $result .= $this->getCSVLine($csvHeaders);
            foreach ($csvData as $row) {
                $result .= $this->getCSVLine($row);
            }
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }

        return $this->response($result, $headers, 200);
    }

    /**
     * Missions Progress Export
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function missionsProgressAction()
    {
        try {
            set_time_limit(3600);
            ini_set("memory_limit", "512M");

            $service = new MissionSearchService();

            $fileName = "Missions_Progress__" . date("Y-m-d H:i:s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;

            $input = $this->getAllInput();
            $result = $service->searchMissionAccount($input, "");
            $result = $result["data"];

            $handle = fopen($file, "w+");
            fwrite($handle, $this->getCSVLine(array(
                "Account ID",
                "Email",
                "Full Name",
                "Created Date",
                "Mission",
                "Mission Status",
                "Mission Started",
                "Mission Ended",
                "Goal",
                "Goal Status",
                "Goal Started",
                "Goal Ended"
            )));

            foreach ($result as $row) {
                $status = "Pending";
                if ($row->mission_goal_is_accomplished) {
                    $status = "Accomplished";
                } else {
                    if ($row->mission_goal_is_started) {
                        $status = "Started";
                    }
                }

                $data = $this->getCSVLine(array(
                    $row->account_id,
                    $row->email,
                    $row->first_name . " " . $row->last_name,
                    $row->created_date,
                    $row->mission_name,
                    ucwords(str_replace("_", " ", $row->mission_status)),
                    $row->mission_date_started,
                    ($row->mission_date_ended != "0000-00-00 00:00:00") ? $row->mission_date_ended : "",
                    $row->mission_goal_name,
                    $status,
                    ($row->mission_goal_date_started != "0000-00-00 00:00:00") ? $row->mission_goal_date_started : "",
                    ($row->mission_goal_date_accomplished != "0000-00-00 00:00:00") ? $row->mission_goal_date_accomplished : "",
                ));

                fwrite($handle, $data);
            }

            fclose($handle);


            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($file));
            header("Cache-Control: must-revalidate");
            header("Expires: 0");
            header("Pragma: public");

            readfile($file);
            unlink($file);

            exit;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }


    /**
     * Refer A Friend Referrals Export
     *
     * @access public
     * @return Response
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    public function referAFriendAction()
    {
        try {
            $service = new ReferralService();

            $data = array(
                "applications" => array(),
                "subscriptions" => array(),
                "eligibles" => array(),
            );

            $fileName = "ReferAFriend__" . date("Y-m-d H:i:s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;

            $input = $this->getAllInput();
            $result = $service->searchReferrals($input, "");
            $result = $result["data"];

            if (!empty($result)) {
                foreach ($result as $referral) {
                    $referralId = $referral->getReferralAccount()->getAccountId();
                    $referredId = $referral->getReferredAccount()->getAccountId();

                    if (!array_key_exists($referralId, $data["eligibles"])) {
                        $data["eligibles"][$referralId] = $this->scholarships->findEligibleNotAppliedScholarshipsIds($referralId);
                    }

                    if (!array_key_exists($referredId, $data["eligibles"])) {
                        $data["eligibles"][$referredId] = $this->scholarships->findEligibleNotAppliedScholarshipsIds($referredId);
                    }

                }
            }

            $handle = fopen($file, "w+");
            fwrite($handle, $this->getCSVLine(array(
                "Referral Full Name",
                "Referral Sign Up Date",
                "Referral Profile Completeness",
                "Referral Eligible Scholarships Count",
                "Referral Applications Count",
                "Referral Paid",
                "Referred Full Name",
                "Referred Sign Up Date",
                "Referred Profile Completeness",
                "Referred Eligible Scholarships Count",
                "Referred Applications Count",
                "Referred Paid"
            )));

            /**
             * @var \App\Entity\Referral[] $result
             */
            foreach ($result as $referral) {
                $referralEligibles = 0;
                $referralPaid = "No";
                $referredEligibles = 0;
                $referredPaid = "No";

                if (array_key_exists($referral->getReferralAccount()->getAccountId(), $data["eligibles"])) {
                    $referralEligibles = count($data["eligibles"][$referral->getReferralAccount()->getAccountId()]);
                }
                if (array_key_exists($referral->getReferredAccount()->getAccountId(), $data["eligibles"])) {
                    $referredEligibles = count($data["eligibles"][$referral->getReferredAccount()->getAccountId()]);
                }
                if ($referral->getReferralAccount()->getIsPaid()) {
                    $referralPaid = "Yes";
                }
                if ($referral->getReferredAccount()->getIsPaid()) {
                    $referredPaid = "Yes";
                }

                $csvLine = $this->getCSVLine(array(
                    $referral->getReferralAccount()->getProfile()->getFullName(),
                    format_date($referral->getReferralAccount()->getCreatedDate()),
                    $referral->getReferralAccount()->getProfile()->getCompleteness(),
                    $referralEligibles,
                    count($referral->getReferralAccount()->getApplications()),
                    $referralPaid,
                    $referral->getReferredAccount()->getProfile()->getFullName(),
                    format_date($referral->getReferredAccount()->getCreatedDate()),
                    $referral->getReferredAccount()->getProfile()->getCompleteness(),
                    $referredEligibles,
                    count($referral->getReferredAccount()->getApplications()),
                    $referredPaid,
                ));

                fwrite($handle, $csvLine);
            }

            fclose($handle);


            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($file));
            header("Cache-Control: must-revalidate");
            header("Expires: 0");
            header("Pragma: public");

            readfile($file);
            unlink($file);

            exit;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    /**
     * Affiliates Responses Export
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */

    public function affiliatesResponsesAction()
    {
        try {
            $buffer = array();
            $bufferSize = 100;

            $service = new AffiliateService();
            $marketingSystemService = new MarketingSystemService();

            $fileName = "AffiliatesResponse_" . date("Y-m-d-H-i-s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;

            $input = $this->getAllInput();
            $result = $service->searchResponses($input, "");
            $result = $result["data"];

            $handle = fopen($file, "w+");
            fwrite($handle, $this->getCSVLine(array(
                "Account ID",
                "Email",
                "First Name",
                "Last Name",
                "Created Date",
                "Affiliate",
                "Goal",
                "URL",
                "Response Date",
                "Aff ID",
                "Aff Sub",
                "Aff Sub2",
                "Aff Sub3",
                "Aff Sub4",
                "Aff Sub5"
            )));

            foreach ($result as $row) {
                $buffer[] = $row;

                if (count($buffer) >= $bufferSize) {
                    $accountIds = array();
                    $emails = array();
                    $hasOffers = array();

                    foreach ($buffer as $bufferRow) {
                        $accountIds[] = $bufferRow["account_id"];
                    }

                    $accountIds = array_unique($accountIds);
                    if (!empty($accountIds)) {
                        $emails = $this->getEmailsListByAccountIds($accountIds);
                        $hasOffers = $marketingSystemService->getMarketingSystemParametersByAccountIds($accountIds);
                    }

                    foreach ($buffer as $bufferRow) {
                        $email = "";
                        $affiliateId = "";
                        $affSub = "";
                        $affSub2 = "";
                        $affSub3 = "";
                        $affSub4 = "";
                        $affSub5 = "";

                        if (array_key_exists($bufferRow["account_id"], $emails)) {
                            $email = @$emails[$bufferRow["account_id"]];
                        }

                        if (array_key_exists($bufferRow["account_id"], $hasOffers)) {
                            $affiliateId = @$hasOffers[$bufferRow["account_id"]]["affiliate_id"];
                            $affSub = @$hasOffers[$bufferRow["account_id"]]["aff_sub"];
                            $affSub2 = @$hasOffers[$bufferRow["account_id"]]["aff_sub2"];
                            $affSub3 = @$hasOffers[$bufferRow["account_id"]]["aff_sub3"];
                            $affSub4 = @$hasOffers[$bufferRow["account_id"]]["aff_sub4"];
                            $affSub5 = @$hasOffers[$bufferRow["account_id"]]["aff_sub5"];
                        }

                        $data = $this->getCSVLine(array(
                            $bufferRow["account_id"],
                            $email,
                            $bufferRow["first_name"],
                            $bufferRow["last_name"],
                            $bufferRow["created_date"],
                            $bufferRow["affiliate_name"],
                            $bufferRow["goal_name"],
                            $bufferRow["url"],
                            $bufferRow["response_date"],
                            $affiliateId,
                            $affSub,
                            $affSub2,
                            $affSub3,
                            $affSub4,
                            $affSub5
                        ));

                        fwrite($handle, $data);
                    }

                    unset($buffer);
                }
            }


            if (!empty($buffer)) {
                $accountIds = array();
                $emails = array();
                $hasOffers = array();

                foreach ($buffer as $bufferRow) {
                    $accountIds[] = $bufferRow["account_id"];
                }

                $accountIds = array_unique($accountIds);
                if (!empty($accountIds)) {
                    $emails = $this->getEmailsListByAccountIds($accountIds);;
                    $hasOffers = $marketingSystemService->getMarketingSystemParametersByAccountIds($accountIds);
                }

                foreach ($buffer as $bufferRow) {
                    $email = "";
                    $affiliateId = "";
                    $affSub = "";
                    $affSub2 = "";
                    $affSub3 = "";
                    $affSub4 = "";
                    $affSub5 = "";

                    if (array_key_exists($bufferRow["account_id"], $emails)) {
                        $email = @$emails[$bufferRow["account_id"]];
                    }

                    if (array_key_exists($bufferRow["account_id"], $hasOffers)) {
                        $affiliateId = @$hasOffers[$bufferRow["account_id"]]["affiliate_id"];
                        $affSub = @$hasOffers[$bufferRow["account_id"]]["aff_sub"];
                        $affSub2 = @$hasOffers[$bufferRow["account_id"]]["aff_sub2"];
                        $affSub3 = @$hasOffers[$bufferRow["account_id"]]["aff_sub3"];
                        $affSub4 = @$hasOffers[$bufferRow["account_id"]]["aff_sub4"];
                        $affSub5 = @$hasOffers[$bufferRow["account_id"]]["aff_sub5"];
                    }

                    $data = $this->getCSVLine(array(
                        $bufferRow["account_id"],
                        $email,
                        $bufferRow["first_name"],
                        $bufferRow["last_name"],
                        $bufferRow["created_date"],
                        $bufferRow["affiliate_name"],
                        $bufferRow["goal_name"],
                        $bufferRow["url"],
                        $bufferRow["response_date"],
                        $affiliateId,
                        $affSub,
                        $affSub2,
                        $affSub3,
                        $affSub4,
                        $affSub5
                    ));

                    fwrite($handle, $data);
                }

                unset($buffer);
            }

            fclose($handle);

            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($file));
            header("Cache-Control: must-revalidate");
            header("Expires: 0");
            header("Pragma: public");

            readfile($file);
            unlink($file);

            exit;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }


    /**
     * Submissions Export
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function submissionsAction()
    {
        try {
            $buffer = array();
            $bufferSize = 100;

            $fileName = "Submissions_" . date("Y-m-d-H-i-s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;


            $input = $this->getAllInput();
            unset($input["page"]);
            foreach ($input as $key => $value) {
                $data["search"][$key] = $value;
            }
            $result = $this->ss->searchSubmissions($data["search"]);

            $handle = fopen($file, "w+");
            fwrite($handle, $this->getCSVLine(array(
                "Submissions ID",
                "Account Id",
                "Username",
                "Email",
                "Ip Address",
                "Name",
                "Status",
                "Response",
                "Send Date"
            )));

            /** @var  Submission $row */
            foreach ($result as $row) {
                $response = $row->getResponse();
                if ($row->getName() == 'Toluna') {
                    $response = str_replace('"', '', (last(explode(PHP_EOL, $response))));
                }
                $data = $this->getCSVLine(array(
                    $row->getSubmissionId(),
                    $row->getAccount()->getAccountId(),
                    $row->getAccount()->getUsername(),
                    $row->getAccount()->getEmail(),
                    $row->getIpAddress(),
                    $row->getName(),
                    $row->getStatus(),
                    $response,
                    $row->getSendDate()
                ));
                fwrite($handle, $data);
            }

            fclose($handle);

            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($file));
            header("Cache-Control: must-revalidate");
            header("Expires: 0");
            header("Pragma: public");

            readfile($file);
            unlink($file);

            exit;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    /**
     * Share Report Export
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function shareReportAction()
    {
        try {
            $buffer = array();
            $bufferSize = 100;

            $service = new ReferralShareService();

            $fileName = "Referral_Shares_" . date("Y-m-d-H-i-s") . ".csv";
            $file = storage_path('framework/cache') . $fileName;

            /*$input = $this->getAllInput();
            unset($input["page"]);
            foreach($input as $key => $value) {
                $data["search"][$key] = $value;
            }
            $result = $service->searchSubmissions($data["search"]);*/

            $result = $service->getShareReport();

            $referral_channels = Referral::getReferralChannels();
            $handle = fopen($file, "w+");
            fwrite($handle, $this->getCSVLine(array_merge(array(
                "User",
                "Account Id",
                "Username",
                "First Shared Date",
                "Last Shared Date",
                "Total Shares"
            ), $referral_channels)));
            foreach ($result["data"] as $row) {
                $data = array(
                    $row["first_name"] . " " . $row["last_name"],
                    $row["account_id"],
                    $row["username"],
                    $row["first_date"],
                    $row["last_date"],
                    $row["total"]
                );
                foreach ($referral_channels as $referral_channel) {
                    array_push($data, isset($row[$referral_channel]) ? $row[$referral_channel] : 0);
                }
                $data = $this->getCSVLine($data);
                fwrite($handle, $data);
            }

            fclose($handle);

            header("Content-Description: File Transfer");
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=" . $fileName);
            header("Content-Length: " . filesize($file));
            header("Cache-Control: must-revalidate");
            header("Expires: 0");
            header("Pragma: public");

            readfile($file);
            unlink($file);

            exit;
        } catch (\Exception $exc) {
            $this->handleException($exc);
        }
    }

    public function edumaxAction()
    {
        $result = "";

        $fileName = "Call_Center_" . date("Y-m-d H:i:s") . ".csv";
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename='$fileName'"
        );

        try {
            set_time_limit(7200);
            ini_set("memory_limit", "1024M");

            $input = $this->getAllInput();
            $domains = Domain::options();
            $csvData = array();

            $searchService = new EdumaxService();
            $loginHistoryService = new LoginHistoryService();

            $searchResult = $searchService->searchAccounts($input, "");
            $accounts = $searchResult["data"];

            $csvHeaders = array(
                "Account ID",
                "Domain",
                "First Name",
                "Last Name",
                "Consent to be Called",
                "Call Link",
                "Admin Link"
            );

            /**
             * @var int $accountId
             * @var Account $account
             */
            foreach ($accounts as $accountId => $account) {
                $profile = $account->getProfile();
                $params = array();

                $loginHistory = $loginHistoryService->getAccountLoginHistory($accountId, "1");

                $callLink = \Config::get("scholarshipowl.edumax.campaign_url");

                $params["IPAddress"] = (isset($loginHistory[0]) && $loginHistory[0]->getIpAddress()) ?
                    $loginHistory[0]->getIpAddress() : \Request::server("SERVER_ADDR");

                $params["FirstName"] = $profile->getFirstName();
                $params["LastName"] = $profile->getLastName();
                $params["Address1"] = $profile->getAddress();

                if ($address2 = $profile->getAddress2()) {
                    $params["Address2"] = $address2;
                }

                if ($categoryId = $searchService->formatCategoryId($profile)) {
                    $params["CategoryId"] = $categoryId;
                }

                if ($militaryAffiliation = $searchService->formatMilitaryAffiliation($profile->getMilitaryAffiliation()->getMilitaryAffiliationId())) {
                    $params["USMilitaryAffiliation"] = $militaryAffiliation;
                }

                if ($stateId = $profile->getState()->getStateId()) {
                    $params["StateProvince"] = \EntityManager::getRepository(State::class)->find($stateId)->getAbbreviation();
                }

                if ($countryId = $profile->getCountry()->getCountryId()) {
                    $params["StateProvince"] = \EntityManager::getRepository(Country::class)->find($countryId)->getAbbreviation();
                }

                $params["City"] = $profile->getCity();
                $params["PostalCode"] = $profile->getZip();
                $params["Country"] = $profile->getCountry()->getAbbreviation();
                $params["HomePhone"] = $profile->getPhone();
                $params["EmailAddress"] = $account->getEmail();
                $params["BirthYear"] = $profile->getDateOfBirthYear();
                $params["Gender"] = $profile->getGender()?ucfirst($profile->getGender()[0]):"";
                $params["Orientation"] = ($profile->getStudyOnline() == "yes") ? "Online" : ($profile->getStudyOnline() == "no") ? "Campus" : "Both";
                $params["USCitizen"] = $searchService->formatCitizenship($profile->getCitizenship()->getCitizenshipId());
                $params["USMilitaryStatus"] = $searchService->formatMilitaryStatus($profile->getMilitaryAffiliation()->getMilitaryAffiliationId());
                $params["CallingOnBehalfOf"] = Domain::find($account->getDomainId())->getName();
                $params["ListID"] = 1;
                $params["RepID"] = "";

                $callLink .= "?" . http_build_query($params);

                $csvData[] = array(
                    $account->getAccountId(),
                    $domains[$account->getDomainId()],
                    $profile->getFirstName(),
                    $profile->getLastName(),
                    $profile->getAgreeCall() ? "Yes" : "No",
                    $callLink,
                    route("admin::accounts.view", ['id' => $account->getAccountId()]),
                );
            }

            $result .= $this->getCSVLine($csvHeaders);
            foreach ($csvData as $row) {
                $result .= $this->getCSVLine($row);
            }
        } catch (\Exception $exc) {
            \Log::error($exc->getMessage());
        }

        return $this->response($result, $headers, 200);
    }

    public function callCenterAction()
    {
        $result = "";

        $fileName = "CallCenter_" . date("Y-m-d H:i:s") . ".csv";
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename='$fileName'"
        );

        try {
            set_time_limit(3600);
            ini_set("memory_limit", "512M");

            $input = $this->getAllInput();
            $csvData = array();

            $ids = array();
            $emails = array();

            $output = preg_split("/(,| |\r\n|\n|\r)/", $input["accounts"]);

            foreach ($output as $row) {
                if (!empty($row)) {
                    if (is_numeric($row)) {
                        $ids[] = $row;
                    } else {
                        $emails[] = $row;
                    }
                }
            }

            /** @var AccountRepository $accountRepository */
            $accountRepository = \EntityManager::getRepository(\App\Entity\Account::class);

            $accountsByIds = $accountRepository->findBy(["accountId" => $ids]);
            $accountsByEmails = $accountRepository->findBy(["email" => $emails]);

            $accounts = array_merge($accountsByIds, $accountsByEmails);

            $csvHeaders = array(
                "Account ID",
                "First Name",
                "Last Name",
                "State",
                "Age",
                "Consent to be Called",
                "Admin URL",
            );

            /**
             * @var int $accountId
             * @var \App\Entity\Account $account
             */
            foreach ($accounts as $accountId => $account) {
                $profile = $account->getProfile();

                $csvData[] = array(
                    $account->getAccountId(),
                    $profile->getFirstName(),
                    $profile->getLastName(),
                    ($profile->getState())?$profile->getState()->getName():"",
                    $profile->getAge(),
                    $profile->getAgreeCall() ? "Yes" : "No",
                    route("admin::accounts.view", ['id' => $account->getAccountId()]),
                );
            }

            $result .= $this->getCSVLine($csvHeaders);
            foreach ($csvData as $row) {
                $result .= $this->getCSVLine($row);
            }
        } catch (\Exception $exc) {
            \Log::error($exc->getMessage());
        }

        return $this->response($result, $headers, 200);
    }

    private function getCSVLine($data)
    {
        $result = array();

        foreach ($data as $value) {
            $result[] = "\"" . $value . "\"";
        }

        return implode(",", $result) . PHP_EOL;
    }

    /**
     * @param $accountIds
     *
     * @return array
     */
    protected function getEmailsListByAccountIds($accountIds): array
    {
        $qb = \EntityManager::getRepository(\App\Entity\Account::class)
            ->createQueryBuilder('a', 'a.accountId')
            ->select(['a.accountId', 'a.email']);
        $accountList = $qb->where(
            $qb->expr()->in('a.accountId', $accountIds)
        )
            ->getQuery()
            ->getResult();
        $emails = [];
        foreach ($accountList as $acc) {
            $emails[$acc['accountId']] = $acc['email'];
        }

        return $emails;
}
}
