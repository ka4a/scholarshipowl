<?php

namespace App\Http\Controllers\Api;
use App\Entity\Account;
use App\Entity\SubscriptionAcquiredType;

/**
 * Subscription Controller
 */

class SubscriptionController extends BaseController {

    /**
     * Subscription Current Action - Gets Current Package (GET)
     * @return Response
     */
    public function currentAction() {
        $model = $this->getOkModel("subscription");
        $data = [];
        try {
        	$subscription = $this->getLoggedUserSubscription();

        	if (!empty($subscription)) {
                $data = array(
                    "subscription_id" => $subscription->getSubscriptionId(),
                    "name" => $subscription->getName(),
                    "price" => $subscription->getPrice(),
                    "credit" => $subscription->getCredit(),
                    "scholarships_count" => $subscription->getScholarshipsCount(),
                    "is_scholarships_unlimited" => $subscription->getIsScholarshipsUnlimited(),
                    "is_paid" => ($subscription->getSubscriptionAcquiredType()->getId() == SubscriptionAcquiredType::PURCHASED) ? "1" : "0",
                    "start_date" => $subscription->getStartDate(),
                    "end_date" => $subscription->getEndDate(),
                    "priority" => $subscription->getPriority(),
                    'isFreeTrial' => $subscription->isFreeTrial(),
                    'activeUntil' => $subscription->getActiveUntil(),
                );
            }

        	$model->setData($data);
        }
        catch (\Exception $exc) {
            $this->handleException($exc);
            $model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
        }

        return $model->send();
    }
}
