<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Account;
use App\Entity\ConversionPagePackage;
use App\Entity\State;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\Entity\Payment\Package;
use ScholarshipOwl\Data\Entity\Payment\SubscriptionAcquiredType;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * Packages Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class PackagesController extends BaseController {
    /**
     * @var EntityManager
     */
    protected $em;


    /**
     * @var array
     */
    protected $sortedStatesList = [];

    /**
     * PackagesController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;

        $this->initDefaultStateList();
    }

    /**
	 * Packages Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/packages/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Packages" => "/admin/packages"
			),
			"title" => "Packages",
			"active" => "packages"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Packages Search Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function searchAction() {
		$model = new ViewModel("admin/packages/table");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Packages" => "/admin/packages",
				"Search Packages" => "/admin/packages/search"
			),
			"title" => "Search Packages",
			"active" => "packages",
			"packages" => array()
		);

		try {
			$service = new PackageService();
			$data["packages"] = $service->getPackages();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Save Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveAction() {
		$model = new ViewModel("admin/packages/save");

        /** @var \App\Entity\Package $freePackage */
        $data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Packages" => "/admin/packages",
			),
			"title" => "Add Package",
			"active" => "packages",
			"package" => new Package(),
			"options" => array(
				"scholarships_unlimited" => array("0" => "No", "1" => "Yes"),
				"active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"mobile_active" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
				"scholarships" => array("" => "--- Select ---", "fixed" => "Fixed", "unlimited" => "Unlimited"),
				"scholarships_selected" => "",
				"expiration_types" => array("" => "--- Select ---") + Package::getExpirationTypes(),
				"expiration_period_types" => array("" => "--- Select ---") + Package::getExpirationPeriodTypes(),
                "expiration_period_values" => array("" => "--- Select ---") + Package::getExpirationPeriodValues(),
                "automatic" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "priority" => array("" => "--- Select ---") + Package::getPriorityOptions(),
			)
		);

        $service = new PackageService();

        $id = $this->getQueryParam("id");
        $data['statesList'] = $this->sortedStatesList;

        if(empty($id)) {
            $data["title"] = "Add Package";
            $data["breadcrumb"]["Add Package"] = "/admin/packages/save";
            $data["options"]["scholarships_selected"] = "";
        }
        else {
            $package = $service->getPackage($id);

            $data["title"] = $package->getName();
            $data["package"] = $package;

            $data["breadcrumb"]["Search Packages"] = "/admin/packages/search";
            $data["breadcrumb"]["Edit Package"] = "/admin/packages/save?id=$id";
            $data["options"]["scholarships_selected"] = $package->isScholarshipsUnlimited() ? "unlimited" : "fixed";
        }

        $data['ph'] = PackageService::placeholderList(true);

		$model->setData($data);

		return $model->send();
	}


	/**
	 * Post Save Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSaveAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();
			$errors = array();

            if(empty($input["name"])) {
				$errors["name"] = "Name is empty !";
			}

			if(!isset($input["is_freemium"])) {
                if (empty($input["price"]) && $input["price"] != "0") {
                    $errors["price"] = "Price is empty !";
                } else {
                    if (!is_numeric($input["price"])) {
                        $errors["price"] = "Price not valid !";
                    }
                }
            }

			if(empty($input["is_active"]) && $input["is_active"] != "0") {
				$errors["is_active"] = "Is active is empty !";
			}

			if(empty($input["scholarships"])) {
				$errors["scholarships"] = "Scholarships is empty !";
			}
			else {
				if($input["scholarships"] == "fixed") {
					if(!isset($input["scholarships_count"]) || !is_numeric($input['scholarships_count'])) {
						$errors["scholarships_count"] = "Scholarships count is empty !";
					}
				}
			}

            if (!empty($input['free_trial'])) {
                if (empty($input['free_trial_period_type'])) {
                    $errors['free_trial_period_type'] = 'Can\'t be empty';
                }
                if (empty($input['free_trial_period_value'])) {
                    $errors['free_trial_period_value'] = 'Can\'t be empty';
                }
            }

            if(empty($input["expiration_type"])) {
				$errors["expiration_type"] = "Expiration type is empty !";
			}
			else {
				if($input["expiration_type"] == Package::EXPIRATION_TYPE_DATE) {
					if(empty($input["expiration_date"]) || $input["expiration_date"] == "0000-00-00") {
						$errors["expiration_date"] = "Expiration date is empty !";
					}
				}
				else if($input["expiration_type"] == Package::EXPIRATION_TYPE_PERIOD) {
					if(empty($input["expiration_period_type"])) {
						$errors["expiration_period_type"] = "Expiration period type is empty !";
					}

                    $input["expiration_period_value"] = $input["expiration_period_value_period"];

					if(empty($input["expiration_period_value"])) {
						$errors["expiration_period_value"] = "Expiration period value is empty !";
					}
					else {
						if(!is_numeric($input["expiration_period_value"])) {
							$errors["expiration_period_value"] = "Expiration period value not valid !";
						}
					}
				}
                else if($input["expiration_type"] == Package::EXPIRATION_TYPE_RECURRENT) {
                    if(empty($input["recurrence_period_type"])) {
                        $errors["recurrence_period_type"] = "Recurrence period type is empty !";
                    }
                    $input["expiration_period_type"] = $input["recurrence_period_type"];

                    if(empty($input["expiration_period_value"])) {
                        $errors["expiration_period_value"] = "Expiration period value is empty !";
                    }
                    else {
                        if(!is_numeric($input["expiration_period_value"])) {
                            $errors["expiration_period_value"] = "Expiration period value not valid !";
                        }
                    }
                }
			}

            if(empty($input["priority"]) && $input["priority"] != "0") {
                $errors["priority"] = "Priority is empty !";
            }

			if(empty($errors)) {
				$service = new PackageService();

				$package = new Package();
				$package->populate($input);

				if($input["scholarships"] == "unlimited") {
					$package->setScholarshipsCount(0);
					$package->setScholarshipsUnlimited(1);
				}
				else {
					$package->setScholarshipsUnlimited(0);
				}

				if(empty($input["package_id"])) {
					$service->addPackage($package);

					$model->setStatus(JsonModel::STATUS_REDIRECT);
					$model->setMessage("Package saved !");
					$model->setData("/admin/packages/search");
				}
				else {
					$service->updatePackage($package);

					$model->setStatus(JsonModel::STATUS_OK);
					$model->setMessage("Package saved !");
				}
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please fix errors !");
				$model->setData($errors);
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}


	/**
	 * Activate Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function activateAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->activatePackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Activate Mobile Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function activateMobileAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->activateMobilePackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Deactivate Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deactivateAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->deactivatePackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Deactivate Mobile Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deactivateMobileAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->deactivateMobilePackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Mark Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function markAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->markPackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Mark Mobile Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function markMobileAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->markMobilePackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Unmark Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function unmarkAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->unmarkPackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}


	/**
	 * Unmark Mobile Package Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function unmarkMobileAction() {
		try {
			$service = new PackageService();
			$id = $this->getQueryParam("id");

			if(!empty($id)) {
				$service->unmarkMobilePackage($id);
			}
			else {
				throw new \Exception("Package id not provided !");
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/packages/search");
	}



	/**
	 * Batch Subscription Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function batchSubscriptionAction() {
		$model = new ViewModel("admin/packages/batch-subscription");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Packages" => "/admin/packages",
				"Batch Subscription" => "/admin/packages/batch-subscription",
			),
			"title" => "Batch Subscription",
			"active" => "packages",
			"flash" => array(
				"data" => "",
				"type" => "",
			),
			"added" => array(),
			"skipped" => array(),
			"options" => array(
				"packages" => array("0" => "--- Select ---"),
			),
		);

		try {
			$packageService = new PackageService();

			$packages = $packageService->getPackages();
			foreach ($packages as $package) {
				$data["options"]["packages"][$package->getPackageId()] = $package->getName();
			}

			$flash = $this->getFlashData();
			if (!empty($flash)) {
				if ($flash["type"] == "success") {
					$result = $flash["data"];
					$addedIds = $result["added"];
					$skippedIds = $result["skipped"];

					if (!empty($addedIds)) {
                        $data["added"] =  $this->getAccountsListByIds($addedIds);
					}

					if (!empty($skippedIds)) {
						$data["skipped"] = $this->getAccountsListByIds($skippedIds);
					}

					$flash["data"] = "CSV imported !";
				}
			}

			$data["flash"] = $flash;
		}
		catch (\Exception $exc) {
			$this->handleException($exc);
		}


		$model->setData($data);
		return $model->send();
	}


	/**
	 * Post Batch Subscription Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postBatchSubscriptionAction()
    {
        $this->validateWith([
            'package_id' => 'required|entity:Package',
            'file' => 'file',
        ]);

        ini_set("memory_limit","512M");
        set_time_limit(0);
        $result = [];

        $accountIds = [];
        $csvFile = \CsvReader::open(\Input::file('file'));
        while ($line = $csvFile->readLine()) {
            if (isset($line[0])) {
                $accountIds[] = $line[0];
            }
        }

        /** @var \App\Entity\Package $package */
        $package = \EntityManager::findById(\App\Entity\Package::class, $this->getQueryParam("package_id"));

        /** @var Account[] $account */
        $accounts = \EntityManager::getRepository(Account::class)->createQueryBuilder('a')
            ->where('a.accountId IN (:accounts)')->getQuery()
            ->iterate(['accounts' => $accountIds]);

        foreach ($accounts as $account) {
            if (\Input::get("remove") === "yes") {
                $subscriptions = \EntityManager::getRepository(Subscription::class)
                    ->findBy(['account' => $account, 'package' => $package]);

                /** @var Subscription $subscription */
                foreach ($subscriptions as $subscription) {
                    \PaymentManager::cancelSubscription($subscription);
                }
            } else {
                \PaymentManager::applyPackageOnAccount($account, $package, SubscriptionAcquiredType::FREEBIE);
            }
        }

        $this->setFlashData($result, "success");

		return $this->redirect("/admin/packages/batch-subscription");
	}

    protected function initDefaultStateList()
    {
        $statesUpcomingPaymentNotifications = config(
            'scholarshipowl.mail.states_upcoming_payment_notifications'
        );
        $statesList = \EntityManager::getRepository(State::class)->findAll();
        /**
         * @var State $state
         */
        foreach ($statesList as $state) {
            if (in_array(
                $state->getAbbreviation(), $statesUpcomingPaymentNotifications
            )
            ) {
                $this->sortedStatesList[] = $state->getName();
            }
        }
    }

    /**
     * @param $addedIds
     *
     * @return array
     */
    protected function getAccountsListByIds($addedIds)
    {
        $qb = \EntityManager::getRepository(\App\Entity\Account::class) ->createQueryBuilder('a', 'a.accountId');
        $query = $qb->where($qb->expr()->in('a.accountId', $addedIds))->getQuery();
        return $query->getResult();
    }
}
