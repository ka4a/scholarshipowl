<?php namespace App\Jobs;

use App\Entity\Account;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class HasOffersPostbackJob extends Job
{
    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param int|Account $account
     * @param string      $url
     */
    public static function dispatch($account, $url)
    {
        dispatch(new static($account, $url));
    }

    /**
     * HasOffersPostback constructor.
     *
     * @param int|Account $account
     * @param string      $url
     */
    public function __construct($account, $url)
    {
        $this->accountId = $account instanceof Account ? $account->getAccountId() : $account;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var null|Account $account */
        if (null === ($account = \EntityManager::find(Account::class, $this->accountId))) {
            $this->failed();
            return;
        }

        $this->trackHasOffers($account, $this->url);
    }

    /**
     * @param Account $account
     * @param         $url
     *
     * @throws \Exception
     */
    protected function trackHasOffers(Account $account, $url)
    {
		$offerId = $this->getHasOffersId($account);
		$goalId = \Config::get("scholarshipowl.hasoffers.goals.$url.$offerId");

		if($account && strlen($goalId) > 0 && is_numeric($goalId)) {
			$service = new MarketingSystemService();
			$marketingSystemAccount = $service->getMarketingSystemAccount($account->getAccountId(), true);

			$savedGoalId = $marketingSystemAccount->getDataValue("goal_id_{$goalId}");
			if(strlen($savedGoalId) == 0) {
				$transactionId = $marketingSystemAccount->getHasOffersTransactionId();
				if(!$transactionId) {
					$transactionId = \Input::get("transaction_id");
					$marketingSystemAccount->addData("transaction_id", $transactionId);
				}

                \HasOffers::info(
                    "HasOffers postback: ".
                    "URL: ".$url."; ".
                    "Account details: ".print_r(logHasoffersAccount($account), true)."; ".
                    "TransactionId: ".$transactionId."; ".
                    "AffiliateId: ".$marketingSystemAccount->getHasOffersAffiliateId()."; ".
                    "GoalId: ".$goalId
                );

				$this->postbackHasOffers($offerId, $goalId, $transactionId);

				$marketingSystemAccount->addData("goal_id_{$goalId}", $goalId);
				$service->setMarketingSystemAccountData($account->getAccountId(), $marketingSystemAccount);
			}
		}
	}

    /**
     * @param $offerId
     * @param $goalId
     * @param $transactionId
     */
	protected function postbackHasOffers($offerId, $goalId, $transactionId)
    {
        if (!is_production()) return;

		// A goalId of zero (0) indicates it is the default conversion
		$url = "";
		if ($goalId == 0) {
			$url = \Config::get("scholarshipowl.hasoffers.url");
			$url = "{$url}offer_id={$offerId}&transaction_id={$transactionId}";
		} else {
			$url = \Config::get("scholarshipowl.hasoffers.url_goal");
			$url = "{$url}goal_id={$goalId}&transaction_id={$transactionId}";
		}

		$response = file_get_contents($url);
		\HasOffers::info("HASOFFERS_GOAL#{$url}#" . $response);
		\Log::info("HASOFFERS_GOAL#{$url}#" . $response);
	}

    /**
     * @param Account|null $account
     *
     * @return mixed|string
     */
	protected function getHasOffersId(Account $account = null)
    {
		$offerId = \Input::get('offer_id');

		if(!$offerId && $account) {
            $service = new MarketingSystemService();
            $marketingSystemAccount = $service->getMarketingSystemAccount($account->getAccountId());
            $offerId = $marketingSystemAccount->getHasOffersOfferId();
		}

		return $offerId;
	}
}
