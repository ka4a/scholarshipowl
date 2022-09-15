<?php

namespace App\Http\Controllers\Api;

use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Subscription;
use App\Facades\EntityManager;
use App\Http\Traits\JsonResponses;
use ScholarshipOwl\Data\Service\Payment\SubscriptionService;
use ScholarshipOwl\Http\AbstractController;
use ScholarshipOwl\Http\JsonModel;


/**
 * Base Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class BaseController extends AbstractController
{
    use JsonResponses;

	const ERROR_CODE_SYSTEM_ERROR = -1;
	const ERROR_CODE_APPLY_NOT_SELECTED = 1000;
	const ERROR_CODE_APPLY_PAID_MEMBERS_ONLY = 1001;
	const ERROR_CODE_APPLY_NO_CREDIT = 1002;
	const ERROR_CODE_ESSAY_NO_SCHOLARSHIP_ID = 2000;
	const ERROR_CODE_ESSAY_NO_ESSAY_ID = 2001;
	const ERROR_CODE_ESSAY_NO_ESSAY_TEXT = 2002;
	const ERROR_CODE_MAILBOX_NO_FOLDER = 3000;
	const ERROR_CODE_MAILBOX_WRONG_FOLDER = 3001;
	const ERROR_CODE_MAILBOX_NO_UID = 3002;
	const ERROR_CODE_EMAIL_INVALID = 4001;
	const ERROR_CODE_NO_SHARE_CHANNEL = 4002;


	private $subscription;


	public function __construct() {
		$this->subscription = null;
        \Session::reflash();
	}

	protected function getOkModel($message, $data = array()) {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_OK);
		$model->setMessage($message);
		$model->setData($data);

		return $model;
	}

	protected function getErrorModel($code, $message = "") {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_ERROR);
		$model->setMessage(empty($message) ? $this->getErrorMessageByCode($code) : $message);
		$model->setData($code);

		return $model;
	}

	protected function getRedirectModel($url, $message = "") {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_REDIRECT);
		$model->setMessage($message);
		$model->setData($url);

		return $model;
	}

	protected function handleException($exc) {
		handle_exception($exc);
	}

    protected function getLoggedUserSubscription() {
        if (!isset($this->subscription)) {
            $user = $this->getLoggedUser();

            if (isset($user)) {
                try {
                    /**
                     * @var SubscriptionRepository $subscriptionRepo
                     */
                    $subscriptionRepo = EntityManager::getRepository(Subscription::class);
                    $subscription = $subscriptionRepo->getTopPrioritySubscription($user);
                    $this->subscription = $subscription;

                    if ($unlimitSubscription = $subscriptionRepo->getUnlimitedScholarshipsSubscription($user)){
                        $this->subscription = $unlimitSubscription;
                    }
                }
                catch (\Exception $exc) {
                    $this->handleException($exc);
                }
            }
        }

        return $this->subscription;
    }

	protected function getErrorMessageByCode($code) {
		$result = "";

		if ($code == self::ERROR_CODE_SYSTEM_ERROR) {
			$result = "System error";
		}
		else if ($code == self::ERROR_CODE_APPLY_NOT_SELECTED) {
			$result = "No selected scholarships";
		}
		else if ($code == self::ERROR_CODE_APPLY_PAID_MEMBERS_ONLY) {
			$result = "No paid member";
		}
		else if ($code == self::ERROR_CODE_APPLY_NO_CREDIT) {
			$result = "No credit";
		}
		else if ($code == self::ERROR_CODE_ESSAY_NO_SCHOLARSHIP_ID) {
			$result = "Essay scholarship id not provided";
		}
		else if ($code == self::ERROR_CODE_ESSAY_NO_ESSAY_ID) {
			$result = "Essay id not provided";
		}
		else if ($code == self::ERROR_CODE_ESSAY_NO_ESSAY_TEXT) {
			$result = "Essay text not provided";
		}
		else if ($code == self::ERROR_CODE_MAILBOX_NO_FOLDER) {
			$result = "Mailbox folder not provided";
		}
		else if ($code == self::ERROR_CODE_MAILBOX_WRONG_FOLDER) {
			$result = "Mailbox folder wrong";
		}

		return $result;
	}

    /**
     * Is Mobile Device
     *
     * @access protected
     * @return bool
     *
     * @author Marko Prelic <markomys@gmail.com>
     */
    protected function isMobile() {
        if (!isset($this->isMobile)) {
            $this->isMobile = is_mobile();
        }

        return $this->isMobile;
    }
}
