<?php namespace App\Services;

use App\Entity\Account;
use App\Entity\Package;
use App\Entity\Subscription;
use App\Payment\IRemoteManager;

class RecurlyService implements IRemoteManager
{
    const CURRENCY = 'USD';

    /**
     * @var array
     */
    protected $accounts = [];

    /**
     * RecurlyService constructor.
     */
    public function __construct()
    {
        \Recurly_Client::$subdomain = config('services.recurly.subdomain');
        \Recurly_Client::$apiKey = config('services.recurly.private_key');
    }

    /**
     * @param string  $token
     * @param Account $account
     * @param Package $package
     *
     * @return \Recurly_Adjustment
     */
    public function charge($token, Account $account, Package $package)
    {
        $recurlyAccount = $this->findAccount($account);
        $recurlyAccount = $this->updateBillingInfo($token, $recurlyAccount);

        $charge = new \Recurly_Adjustment();
        $charge->account_code = $recurlyAccount->account_code;
        $charge->currency = static::CURRENCY;
        $charge->unit_amount_in_cents = $package->getPriceInCents();
        $charge->create();

        return $charge;
    }

    /**
     * @param string  $token
     * @param Account $account
     * @param Package $package
     *
     * @return \Recurly_Subscription
     */
    public function subscribe($token, Account $account, Package $package)
    {
        $recurlyAccount = $this->findAccount($account);
        $recurlyAccount = $this->updateBillingInfo($token, $recurlyAccount);

        $recurlySubscription = new \Recurly_Subscription();
        $recurlySubscription->plan_code = $package->getRecurlyPlan();
        $recurlySubscription->currency = static::CURRENCY;
        $recurlySubscription->account = $recurlyAccount;
        $recurlySubscription->create();

        return $recurlySubscription;
    }

    /**
     * Cancel subscription on recurly
     *
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function cancelSubscription(Subscription $subscription)
    {
        $subscription = $this->findSubscription($subscription);
        $subscription->cancel();

        $this->failInvoices($subscription);

        return $this;
    }

    /**
     * @param Subscription $subscription
     *
     * @return $this
     */
    public function terminateSubscription(Subscription $subscription)
    {
        $recurlySubscription = $this->findSubscription($subscription);
        $recurlySubscription->terminateWithoutRefund();

        $this->failInvoices($recurlySubscription);

        return $this;
    }

    /**
     * @param \Recurly_Subscription $subscription
     */
    protected function failInvoices(\Recurly_Subscription $subscription)
    {
        /** @var \Recurly_Invoice $invoice */
        $invoice = $subscription->invoice->get();

        if ($invoice && isset($invoice->state) && in_array($invoice->state, ['open', 'past_due'])) {
            $invoice->markFailed();
        }
    }

    /**
     * @param Account $account
     *
     * @return \Recurly_Account
     */
    protected function findAccount(Account $account)
    {
        if (!isset($this->accounts[$account->getAccountId()])) {
            try {
                $recurlyAccount = \Recurly_Account::get($account->getUsername());
            } catch (\Recurly_NotFoundError $e) {
                $recurlyAccount = $this->createAccount($account);
            }

            $this->accounts[$account->getAccountId()] = $recurlyAccount;
        }

        return $this->accounts[$account->getAccountId()];
    }

    /**
     * @param Subscription $subscription
     *
     * @return \Recurly_Subscription
     */
    protected function findSubscription(Subscription $subscription)
    {
        return \Recurly_Subscription::get($subscription->getExternalId());
    }

    /**
     * @param                  $token
     * @param \Recurly_Account $account
     *
     * @return \Recurly_BillingInfo
     */
    protected function updateBillingInfo($token, \Recurly_Account $account)
    {
        $billingInfo = new \Recurly_BillingInfo();
        $billingInfo->account_code = $account->account_code;
        $billingInfo->currency = static::CURRENCY;
        $billingInfo->token_id = $token;
        $billingInfo->create();

        $account->billing_info = $billingInfo;

        return $account;
    }

    /**
     * @param Account $account
     *
     * @return \Recurly_Account
     */
    protected function createAccount(Account $account)
    {
        $recurlyAccount = new \Recurly_Account();
        $recurlyAccount->account_code = $account->getUsername();
        $recurlyAccount->username = $account->getUsername();
        $recurlyAccount->email = $account->getEmail();
        $recurlyAccount->first_name = $account->getProfile()->getFirstName();
        $recurlyAccount->last_name = $account->getProfile()->getLastName();
        $recurlyAccount->create();

        return $recurlyAccount;
    }
}
