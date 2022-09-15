<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Domain;
use App\Entity\Resource\Resource;
use App\Entity\TransactionPaymentType;
use App\Facades\EntityManager;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\Payment\PaymentMethod;
use ScholarshipOwl\Data\Entity\Payment\Transaction;
use ScholarshipOwl\Data\Entity\Payment\TransactionStatus;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\TransactionService;
use ScholarshipOwl\Domain\Subscription;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Http\JsonModel;


/**
 * Transaction Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class TransactionController extends BaseController {
	/**
	 * Transaction Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/transactions/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Transactions" => "/admin/transactions",
			),
			"title" => "Transactions",
			"active" => "transactions"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Transaction Search Action
	 *
	 * @access public
     * @deprecated Method should be rewritten by using only doctrine objects
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function searchAction() {
		$packageService = new PackageService();

		$packages = array();

		foreach ($packageService->getPackages() as $package){
			$packages[$package->getPackageId()] = $package->getName();
		}

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Transactions" => "/admin/transactions",
				"Search Transactions" => "/admin/transactions/search"
			),
			"title" => "Search Transactions",
			"active" => "transactions",
			"transactions" => array(),
			"count" => 0,
			"amount" => 0.00,
			"search" => array(
                'domain' => '',
                'first_name' => '',
                'last_name' => '',
				"transaction_status_id" => "",
                "subscription_expiration_type" => "",
				"payment_method_id" => "",
                "payment_type_id" => "",
				"amount_min" => "",
				"amount_max" => "",
				"provider_transaction_id" => "",
				"bank_transaction_id" => "",
				"created_date_from" => "",
				"created_date_to" => "",
                'subscription_start_date_from' => '',
                'subscription_start_date_to' => '',
				"credit_card_type" => "",
				"device" => "",
				"affiliate_id" => "",
				"expiration_type" => "",
				"package" => "",
				"recurrent_number" => "",
                "recurrent_number_gt" => "",
                "recurrent_number_lt" => "",
				"account_id" => "",
				"phone" => "",
                'package_free_trial' => null,
			),
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/transactions/search",
				"url_params" => array()
			),
			"options" => array(
                'domains' => Domain::options(),
				"payment_methods" => PaymentMethod::getPaymentMethods(),
                "payment_types" => TransactionPaymentType::options(),
				"transaction_statuses" => TransactionStatus::getTransactionStatuses(),
                "subscription_expiration_types" => Package::getExpirationTypes(),
				"credit_card_types" => array("MasterCard" => "MasterCard",  "Visa" => "Visa", "Discover" => "Discover", "Maestro" => "Maestro", "Amex" => "Amex"),
				"devices" => Transaction::getDevices(),
				"expiration_types" => Package::getExpirationTypes(),
				"packages" => $packages,
                'package_free_trial' => [
                    '' => 'Not selected',
                    '1' => 'Yes',
                    '0' => 'No',
                ],
			)
		);


        $transactionService = new TransactionService();

        $display = 50;
        $pagination = $this->getPagination($display);

        $input = $this->getAllInput();
        unset($input["page"]);
        foreach($input as $key => $value) {
            $data["search"][$key] = $value;
        }

        $searchResult = $transactionService->searchTransactions($data["search"], $pagination["limit"]);

        $doctrineTransactions = [];
        foreach ($searchResult["data"] as $result) {
            $transaction = new Resource(\EntityManager::findById(
                \App\Entity\Transaction::class, $result->getTransactionId()
            ), true);

            $transaction->has_offers_affiliate_id = $result->has_offers_affiliate_id;
            $transaction->has_offers_transaction_id = $result->has_offers_transaction_id;
            $doctrineTransactions[] = $transaction;
        }

        $data["transactions"] = $doctrineTransactions;
        $data["count"] = $searchResult["count"];
        $data["amount"] = $searchResult["amount"];
        $data["pagination"]["page"] = $pagination["page"];
        $data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
        $data["pagination"]["url_params"] = $data["search"];

		return view('admin.transactions.search', $data);
	}

	/**
	 * Transaction View Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function viewAction() {
		$data = [
			"user" => $this->getLoggedUser(),
			"breadcrumb" => [
				"Dashboard" => "/admin/dashboard",
				"Transactions" => "/admin/transactions",
				"Search Transactions" => "/admin/transactions/search",
			],
			"title" => "View Transaction",
			"active" => "transactions",
			"transaction" => null,
		];

		try {
			$id = $this->getQueryParam("id");

			if (empty($id)) {
				throw new \Exception("Transaction id not provided");
			}

			$data["transaction"] = new Resource(\EntityManager::findById(\App\Entity\Transaction::class, $id));
			$data["breadcrumb"]["View Transaction"] = route('admin::transactions.view', ['id' => $id]);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return view('admin.transactions.view', $data);
	}


	/**
	 * Change Status Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function changeStatusAction() {
		$model = new ViewModel("admin/transactions/change_status");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Transactions" => "/admin/transactions",
				"Search Transactions" => "/admin/transactions/search",
			),
			"title" => "Change Transaction Status",
			"active" => "transactions",
			"transaction" => null,
			"statuses" => array(),
		);

		try {
			$id = $this->getQueryParam("id");

			if (empty($id)) {
				throw new \Exception("Transaction id not provided");
			}

            /** @var \App\Entity\Transaction $transaction */
            $transaction = \EntityManager::getRepository(\App\Entity\Transaction::class)
                ->findOneBy(['transactionId' => $id]);

			$statuses = TransactionStatus::getTransactionStatuses();

			$data["transaction"] = $transaction;
			$data["statuses"] = $statuses;
			$data["breadcrumb"]["Change Transaction Status"] = "/admin/transactions/change-status?id=$id";
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Change transaction status
	 *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
	public function postChangeStatusAction() {
		$model = new JsonModel();

        $transactionId = $this->getQueryParam("transaction_id");
        $transactionStatusId = $this->getQueryParam("transaction_status_id");

        if (empty($transactionId)) {
            throw new \Exception("Transaction id not provided");
        }

        if (empty($transactionStatusId)) {
            throw new \Exception("Transaction status id not provided");
        }

        /** @var \App\Entity\Transaction $transaction */
        $transaction = \EntityManager::getRepository(\App\Entity\Transaction::class)
            ->findOneBy(['transactionId' => $transactionId]);

        $transaction->setTransactionStatus($transactionStatusId);
        \EntityManager::persist($transaction);
        \EntityManager::flush($transaction);

        // set active_until date for subscription to be the date of refund (now)
        if ((int)$transactionStatusId === \App\Entity\TransactionStatus::REFUND) {
            $subscription = $transaction->getSubscription();
            $now =  new \DateTime();
            if ($subscription->getActiveUntil() > $now) {
                $subscription->setActiveUntil(new \DateTime());
                \EntityManager::flush($subscription);
            }
        }

        $model->setStatus(JsonModel::STATUS_OK);
        $model->setMessage("Transaction status changed!");

		return $model->send();
	}
}
