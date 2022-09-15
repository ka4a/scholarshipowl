<?php
/**
 * Created by PhpStorm.
 * User: vadimkrutov
 * Date: 15/06/16
 * Time: 21:13
 */

namespace App\Services\Zendesk;
use App\Entity\Log\LoginHistory;
use App\Entity\Subscription;
use App\Entity\Account;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Zendesk;
use EntityManager;
use Zendesk\API\HttpClient as ZendAPI;

class ZendeskService
{
	/**
	 * @var ZendAPI $zendeskClient
	 */
	public $zendeskClient;

    /**
     * @var array
     */
    protected $config;

    /**
     * ZendeskService constructor.
     *
     * @param array   $config
     * @param ZendAPI $client
     */
	public function __construct(array $config, ZendAPI $client)
	{
        $this->config = $config;
		$this->zendeskClient = $client;
	}

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->config['enabled'];
    }

    public function createUser(Account $account)
	{
        if (!$this->isEnabled()) {
            return false;
        }

		if ($account->getZendeskUserId() == null) {
		    // we may have several accounts with the same email. We must not submit them twice to Zendesk
		    $accountsByEmail = \EntityManager::getRepository(\App\Entity\Account::class)
		        ->findBy(['email' => $account->getEmail()]);

		    if (count($accountsByEmail) > 1) {
                /** @var Account $acc */
                foreach ($accountsByEmail as $acc) {
		            if ($acc->getAccountId() != $account->getAccountId() && $acc->getZendeskUserId()) {
		                $account->setZendeskUserId($acc->getZendeskUserId());
		                EntityManager::flush($account);

		                return false;
		            }
		        }
		    }

			$user = $this->zendeskClient->users()->create($this->getZendeskData($account));
			if (is_object($user)) {
				$account->setZendeskUserId($user->user->id);

				EntityManager::flush($account);
			}
		}
	}

    /**
     * Notice, if we send multiple updates to Zendesk nearly at the same time for the same account
     * it can cause 409 error, that's why we check time here and the data changes.
     *
     * @param Account $account
     * @return void
     */
	public function updateUser(Account $account)
	{
        if (!$this->isEnabled()) {
            return;
        }

        if ($account->getZendeskUserId() != null) {
            $data = $this->getZendeskData($account);

            $cacheKeyData = "zendesk_last_data_hash_{$account->getAccountId()}";
            $cacheKeyTime = "zendesk_last_update_time_{$account->getAccountId()}";

            $md5Data = md5(json_encode($data));

            if (\Cache::get($cacheKeyData) === $md5Data) {
                return;
            }

            $lastUpdateTs = \Cache::get($cacheKeyTime, 0);
            if ((time() - $lastUpdateTs) < 10) {
                sleep(10);
            }

            \Cache::put($cacheKeyData, $md5Data, 30 * 60);
            \Cache::put($cacheKeyTime, time(), 30 * 60);

            $this->zendeskClient->users()->update($account->getZendeskUserId(), $data);
        }
	}

    /**
     * @param string $subject
     * @param string $body
     */
    public function createTicket(string $subject, string $body)
    {
        if (!$this->isEnabled()) {
            return false;
        }

        if($subject != null && $body != null)
        {
            $this->zendeskClient->tickets()->create([
                'subject' => $subject,
                'comment' => ['body' => $body],
                'priority' => 'high'
                ]
            );
        }
        else
        {
            throw new BadRequestHttpException('Bad Request');
        }
    }

    /**
     * Method that iterates over all account entries and updates or creates users on zendesk
     */
    public function updateAllUsers()
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $q = \EntityManager::getRepository(Account::class)->createQueryBuilder('a')->getQuery()->iterate();

        /**
         * @var Account $user
         */
        foreach ($q as $user)
        {
            $user = $user[0];
            if($user->getZendeskUserId() == null)
            {
                $this->createUser($user);
            }
            else
            {
                $this->updateUser($user);
            }

            \EntityManager::detach($user);
        }
    }

    /**
	 * Method that parses data for Zendesk
	 * This method is used for all of registration phases as well as for all profile editing events
	 *
	 * @param Account $account
	 *
	 * @return array
	 */
	private function getZendeskData(Account $account)
	{
		$data = [
            'domain' => $account->getDomain()->getName(),
        ];

		//Registration step 1 - which means all of those fields always will exist or profile editing
		if($account->getProfile()->getFirstName() !== null && $account->getProfile()->getLastName())
		{
			$data['name'] = $account->getProfile()->getFirstName() . ' ' . $account->getProfile()->getLastName();
		}

		if($account->getEmail() != null && $account->getEmail() != "")
		{
			$data['email'] = $account->getEmail();
			$data['user_fields']['admin_url'] = route('admin::accounts.view', ['id' => $account->getAccountId()]);
		}

		if($account->getProfile()->getPhone() !== null && $account->getProfile()->getPhone() != "")
		{
			$data['phone'] = $account->getProfile()->getPhone();
		}

        if($account->getCreatedDate() !== null)
        {
            $data['user_fields']['registered_at'] = $account->getCreatedDate()->format('Y-m-d');
        }

        if($account->getUsername() !== null)
        {
            $data['user_fields']['username'] = $account->getUsername();
        }

        if($account->getReferralCode() !== null)
        {
            $data['user_fields']['referral_code'] = $account->getReferralCode();
        }

		//Registration step 2 or profile editing
		if($account->getProfile()->getSchoolLevel() !== null)
		{
			$data['user_fields']['school_level'] =  $account->getProfile()->getSchoolLevel()->getName();
		}

		if($account->getProfile()->getDateOfBirth() !== null && $account->getProfile()->getDateOfBirth() != "")
		{
			$data['user_fields']['date_of_birth'] =  $account->getProfile()->getDateOfBirth()->format('Y-m-d');
		}

		if($account->getProfile()->getGender() !== null)
		{
			$data['user_fields']['gender'] =  $account->getProfile()->getGender();
		}

		if($account->getProfile()->getEthnicity() !== null)
		{
			$data['user_fields']['ethnicity'] =  $account->getProfile()->getEthnicity()->getName();
		}

		if($account->getProfile()->getCitizenship() !== null)
		{
			$data['user_fields']['citizenship'] = $account->getProfile()->getCitizenship()->getName();
		}

		if($account->getProfile()->getHighSchool() !== null && $account->getProfile()->getHighSchool() != "")
		{
			$data['user_fields']['high_school'] = $account->getProfile()->getHighSchool();
		}

		if($account->getProfile()->getEnrolled() !== null)
		{
			$data['user_fields']['enrolled_in_college'] = $account->getProfile()->getEnrolled() == '1' ? 'yes' : 'no';
		}

		if($account->getProfile()->getEnrollmentMonth() !== null && $account->getProfile()->getEnrollmentYear() !== null)
		{
			$data['user_fields']['college_enrollment_date'] =  $account->getProfile()->getEnrollmentMonth() . '/' . $account->getProfile()->getEnrollmentYear();
		}

		if($account->getProfile()->getGpa() !== null && $account->getProfile()->getGpa() != "")
		{
			$data['user_fields']['gpa'] = $account->getProfile()->getGpa();
		}

		if($account->getProfile()->getGraduationMonth() != null && $account->getProfile()->getGraduationYear() != null)
		{
			$data['user_fields']['graduation'] = $account->getProfile()->getGraduationMonth() . '/' . $account->getProfile()->getGraduationYear();
		}

		if($account->getProfile()->getDegreeType())
		{
			$data['user_fields']['degree'] = $account->getProfile()->getDegreeType()->getName();
		}

		if($account->getProfile()->getCareerGoal() !== null)
		{
			$data['user_fields']['career_goal'] = $account->getProfile()->getCareerGoal()->getName();
		}

		if($account->getProfile()->getStudyOnline() !== null)
		{
			$data['user_fields']['interested_studying_online'] = $account->getProfile()->getStudyOnline();
		}

		if($account->getProfile()->getUniversity() !== null)
		{
			$data['user_fields']['university_1'] = $account->getProfile()->getUniversity();
		}

		//Registration Step 3 or profile editing
		if($account->getProfile()->getAddress() !== null && $account->getProfile()->getZip() != "")
		{
			$data['user_fields']['address'] = $account->getProfile()->getAddress();
		}

		if($account->getProfile()->getZip() !== null && $account->getProfile()->getZip() != "")
		{
			$data['user_fields']['zip_code'] = $account->getProfile()->getZip();
		}

		if($account->getProfile()->getCity() !== null && $account->getProfile()->getCity() != "")
		{
			$data['user_fields']['city'] = $account->getProfile()->getCity();
		}

		if($account->getProfile()->getState() !== null)
		{
			$data['user_fields']['state'] = $account->getProfile()->getState()->getName() . '('.$account->getProfile()->getState()->getAbbreviation().')';
		}

		if($account->getProfile()->getProfileType() !== null)
		{
			$data['user_fields']['profile_type'] = $account->getProfile()->getProfileType();
		}

		/**
		 * @var Subscription $subscription
		 */
		$subscription = \EntityManager::getRepository(\App\Entity\Subscription::class)->getTopPrioritySubscription($account);

		//Process subscription
		if($subscription)
		{
			$data['user_fields']['subscription_name'] = $subscription->getName();
			$data['user_fields']['subscription_acquired_type'] = $subscription->getSubscriptionAcquiredType() != null ? $subscription->getSubscriptionAcquiredType()->getName() : '';
			$data['user_fields']['subscription_first_billing_date'] = $subscription->getStartDate()->format('Y-m-d');
			$data['user_fields']['subscription_next_billing_date'] = $subscription->getRenewalDate()->format('Y-m-d');
			$data['user_fields']['subscription_status'] = $subscription->getSubscriptionStatus() != null ? $subscription->getSubscriptionStatus()->getName() : '';

			$data['user_fields']['admin_subscriptions_url'] = route('admin::accounts.subscriptions', ['id' => $account->getAccountId()]);
		}

        /**
         * @var LoginHistory $loginHistory
         */
        $loginHistory = EntityManager::getRepository(LoginHistory::class)->findOneBy(
            [
                'account' => $account,
                'action' => LoginHistory::ACTION_LOGIN,
            ],
            [
                'loginHistoryId' => 'DESC'
            ]
        );

		//Process login history
		if($loginHistory)
		{
			$data['user_fields']['last_login_ip'] = $loginHistory->getIpAddress();
			$data['user_fields']['last_login_date'] =  $loginHistory->getActionDate()->format('Y-m-d H:i:s');
		}

        if($account->getProfile()->getCompleteness() >= 1)
        {
            $data['user_fields']['profile_completeness'] = $account->getProfile()->getCompleteness();
        }

        $data['user_fields']['admin_login_history_url'] = route('admin::accounts.loginHistory', ['id' => $account->getAccountId()]);

		return $data;
	}

    /**
     * @param Subscription $subscription
     */
    public function cancellationTicket(Subscription $subscription)
    {
        $this->createTicket(
            "Membership cancellation " . date("Y-m-d H:i:s"). '[Site generated request]',
            sprintf(
                "Subscription: %s (%s)\n".
                "Account ID: %s\n".
                "Payment proccessor: %s\n".
                "Email: %s\n".
                "Next billing date: %s\n",
                $subscription->getName(),
                $subscription->getSubscriptionId(),
                $subscription->getAccount()->getAccountId(),
                $subscription->getPaymentMethod(),
                $subscription->getAccount()->getEmail(),
                $subscription->getRenewalDate()->format('Y-m-d H:i:s')
            )
        );
    }
}
