<?php

namespace App\Http\Controllers\Admin;

use App\Entity\ApplicationStatus;
use App\Entity\Log\LoginHistory;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\Resource;
use App\Entity\Resource\ScholarshipResource;
use App\Entity\Scholarship;
use App\Entity\SubscriptionAcquiredType;
use Doctrine\Common\Collections\Criteria;
use ScholarshipOwl\Data\Entity\Statistic\StatisticDaily;
use ScholarshipOwl\Data\Entity\Statistic\StatisticDailyType;
use ScholarshipOwl\Data\ResourceCollection;
use ScholarshipOwl\Data\Service\Payment\ApplicationService;
use ScholarshipOwl\Data\Service\Statistic\StatisticDailyService;
use ScholarshipOwl\Http\ViewModel;
use Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\Account;


use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\Payment\Transaction;
use ScholarshipOwl\Data\Entity\Payment\Subscription;

use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\TransactionService;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;

/**
 * Statistic Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class StatisticController extends BaseController {

	/**
	 * Statistics Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/statistics/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Statistics" => "/admin/statistics"
			),
			"title" => "Statistics",
			"active" => "statistics",
			"search" => array(
				'from' => '',
				'to'   => '',
			),
			'customers' => 0,
			'applications' => 0,
			'amount' => 0,
			'transactions' => 0,
			'packageTotals' => array(),
			'packages' => array(),
		);

		try {
			$input = $this->getAllInput();
			$for = @$input['for'];
			unset($input['for']);
			foreach ($input as $key => $value) {
				$data['search'][$key] = $value;
			}

			if(!empty($for)) {
				if($for == "scholarships") {
					$data["title"] = "Scholarships Statistics";
				}
				else if($for == "accounts") {
					$data["title"] = "Accounts Statistics";
				}
				else if($for == "transactions") {
					$data['title'] = 'Transaction Reports By Date';

					$service = new PackageService();
					$data['packages'] = $service->getPackages();

					$transactionsService = new TransactionService();
					$subscriptionService = new SubscriptionService();
					$data['applications']  = $subscriptionService->getScholarshipApplicationsDated($data['search']['from'], $data['search']['to']);
					$data['customers']     = $subscriptionService->getUniqueCustomersDated($data['search']['from'], $data['search']['to']);
					$data['transactions']  = $transactionsService->getTransactionsDated($data['search']['from'], $data['search']['to']);
					$data['amount']        = $subscriptionService->getTotalAmountDated($data['search']['from'], $data['search']['to']);
					$data['packageTotals'] = $subscriptionService->getTotalsByPackageDated($data['search']['from'], $data['search']['to']);
				}
				else {
					throw new \Exception("Unknown statistics option");
				}

				$data["breadcrumb"] += array($data["title"] => "statistics?for=$for");
				$model->setFile("admin/statistics/$for");
			}
			else {
				$data["title"] = "Statistics";
				$data["breadcrumb"] += array($data["title"] => "statistics");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}

    /**
     * Statistic Search Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function dailyManagementStatisticAction() {
        $model = new ViewModel("admin/statistics/daily-management");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Statistics" => "/admin/statistics",
                "Daily Management Report" => "/admin/statistics/daily-management"
            ),
            "title" => "Daily Management Report",
            "active" => "statistics",
            "statistics" => array(),
            "count" => 0,
            "search" => array(
                "statistic_daily_type_id" => array(),
                "statistic_daily_date_from" => "",
                "statistic_daily_date_to" => "",
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/statistics/daily-management",
                "url_params" => array()
            ),
            "options" => array(
                "statistic_daily_types" => StatisticDailyType::getStatisticDailyTypes()
            )
        );

        $statisticDailyService = new StatisticDailyService();

        $display = 50 * count(StatisticDailyType::getStatisticDailyTypes());
        $pagination = $this->getPagination($display);

        $input = $this->getAllInput();

        unset($input["page"]);
        foreach($input as $key => $value) {
            $data["search"][$key] = $value;
        }

        $searchResult = $statisticDailyService->search($data["search"], $pagination["limit"]);

        $data["statistics"] = $searchResult["data"];

        $data["count"] = $searchResult["count"];
        $data["pagination"]["page"] = $pagination["page"];
        $data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
        $data["pagination"]["url_params"] = $data["search"];

        $model->setData($data);
        return $model->send();
    }

    /**
     * CustomerDataReport
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function customerDataReportAction() {
        $model = new ViewModel("admin/statistics/customer-report");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Statistics" => "/admin/statistics",
                "Daily Management Report" => "/admin/statistics/customer-report"
            ),
            "title" => "Customer Data Report",
            "active" => "statistics",
            "accounts" => array(),
            "applicationsCount" => array(),
            "applicationsWithEssayCount" => array(),
            "count" => 0,
            "search" => array(
                "customer_report_date_from" => "",
                "customer_report_date_to" => "",
                "customer_report_name" => "",
            ),
            "pagination" => array(
                "page" => 1,
                "pages" => 0,
                "url" => "/admin/statistics/customer-report",
                "url_params" => array()
            ),
            "options" => array(
                "statistic_daily_types" => StatisticDailyType::getStatisticDailyTypes()
            )
        );

        try {
            $page = $this->getQueryParam("page", 1);

            $dql = "SELECT a FROM App\Entity\Account a JOIN a.subscriptions s join a.applications ap WHERE s.subscriptionAcquiredType IN (?1) ORDER BY a.accountId DESC";

            $query = \EntityManager::createQuery($dql)
                ->setParameter(1, array(SubscriptionAcquiredType::PURCHASED, SubscriptionAcquiredType::MISSION, SubscriptionAcquiredType::REFERRAL, SubscriptionAcquiredType::REFERRED))
                ->setFirstResult(($page - 1)* 50)
                ->setMaxResults(50);

            $paginator = new Paginator($query);
            $data["count"] = count($paginator);
            $accountIds = array();

            /** @var Account $accountEntity */
            foreach ($paginator as $accountEntity){
                $account = new Resource($accountEntity);
                $data["accounts"][$accountEntity->getAccountId()]["account"] = $account;

                /** @var \App\Entity\Subscription $subscription */
                $subscription = $accountEntity->getSubscriptions()->last();
                if($subscription !== null){
                    $data["accounts"][$accountEntity->getAccountId()]["subscription"] = $subscription;
                    $transaction = $subscription->getTransactions()->last();
                    if ($transaction !== null){
                        $data["accounts"][$accountEntity->getAccountId()]["transaction"] = $transaction;
                    }
                }

                $data["accounts"][$accountEntity->getAccountId()]["textCount"] = $accountEntity->getApplicationText()->count();

                $loginCount = \EntityManager::createQuery("SELECT COUNT(lh) FROM \App\Entity\Log\LoginHistory lh WHERE lh.action = ?1 AND lh.account = ?2");
                $loginCount->setParameter(1, LoginHistory::ACTION_LOGIN);
                $loginCount->setParameter(2, $accountEntity);

                $data["accounts"][$accountEntity->getAccountId()]["loginCount"] = $loginCount->getSingleScalarResult();

                $loginCountMonth = \EntityManager::createQuery("SELECT COUNT(lh) FROM \App\Entity\Log\LoginHistory lh WHERE lh.action = ?1 AND lh.account = ?2 AND YEAR(lh.actionDate) = YEAR(CURRENT_DATE()) AND MONTH(lh.actionDate) = MONTH(CURRENT_DATE())");
                $loginCountMonth->setParameter(1, LoginHistory::ACTION_LOGIN);
                $loginCountMonth->setParameter(2, $accountEntity);
                $data["accounts"][$accountEntity->getAccountId()]["loginCountMonth"] = $loginCountMonth->getSingleScalarResult();

                $loginCountPreviousMonth = \EntityManager::createQuery("SELECT COUNT(lh) FROM \App\Entity\Log\LoginHistory lh WHERE lh.action = ?1 AND lh.account = ?2 AND YEAR(lh.actionDate) = YEAR(DATE_SUB(CURRENT_DATE() , 1, 'MONTH')) AND MONTH(lh.actionDate) = MONTH(DATE_SUB(CURRENT_DATE() , 1, 'MONTH'))");
                $loginCountPreviousMonth->setParameter(1, LoginHistory::ACTION_LOGIN);
                $loginCountPreviousMonth->setParameter(2, $accountEntity);
                $data["accounts"][$accountEntity->getAccountId()]["loginCountPreviousMonth"] = $loginCountPreviousMonth->getSingleScalarResult();

                $historyRepository = \EntityManager::getRepository(LoginHistory::class)->findBy(
                    ['account' => $accountEntity->getAccountId(), 'action' => LoginHistory::ACTION_LOGIN],
                    ['loginHistoryId' => 'DESC'],
                    1
                );
                $data["accounts"][$accountEntity->getAccountId()]["lastLogin"] = Resource::getResourceCollection($historyRepository);

                $data["accounts"][$accountEntity->getAccountId()]["lastApplication"] = !$accountEntity->getApplications()->isEmpty()?$accountEntity->getApplications()->last():null;

                /** @var ScholarshipRepository $scholarshipRepository */
                $scholarshipRepository = \EntityManager::getRepository(Scholarship::class);
                $data["accounts"][$accountEntity->getAccountId()]["scholarshipCount"] = $scholarshipRepository->countEligibleScholarships($accountEntity);
                $data["accounts"][$accountEntity->getAccountId()]["agreeCall"] = $accountEntity->getProfile()->getAgreeCall();
                $data["accounts"][$accountEntity->getAccountId()]["onboardingCalls"] = $accountEntity->getAccountOnBoardingCall();

                $accountIds[] = $accountEntity->getAccountId();
            }

            $applicationService = new ApplicationService();
            $data["applicationsWithRequirementsCount"] = $applicationService->getSubmittedApplicationsWithRequirementsCount($accountIds);
            $data["lastEssaySubmitted"] = $applicationService->getLastSubmittedApplicationWithEssay($accountIds);

            $data["pagination"]["page"] = $page;
            $data["pagination"]["pages"] = ceil($data["count"] / 50);
            $data["pagination"]["url_params"] = $data["search"];
        }
        catch(\Exception $exc) {
            $this->handleException($exc);
        }
        $model->setData($data);
        return $model->send();
    }
}
