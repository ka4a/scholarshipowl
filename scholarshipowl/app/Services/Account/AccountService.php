<?php namespace App\Services\Account;

use App\Entity\Account;
use App\Entity\Country;
use App\Entity\Profile;
use App\Entity\Repository\AccountRepository;
use App\Entity\SocialAccount;
use App\Entity\Subscription;
use App\Entity\SubscriptionStatus;
use App\Events\Account\CreateAccountEvent;
use App\Events\Account\DeleteAccountEvent;
use App\Events\Account\DeleteTestAccountEvent;
use App\Events\Account\UpdateAccountEvent;
use App\Http\Traits\ValidatesArray;
use App\Jobs\UpdateSubmissions;
use App\Payment\RemotePaymentManager;
use App\Services\DateService;
use App\Services\DomainService;
use App\Services\EligibilityService;
use App\Services\FacebookService;
use App\Services\PasswordService;
use Doctrine\ORM\EntityManager;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\GraphNodes\GraphUser;
use Illuminate\Database\Query\Builder;

class AccountService
{
    use ValidatesArray;

    const VALIDATE_REGISTER = 'register';
    const VALIDATE_UPDATE = 'update';
    const VALIDATE_REGISTER_FACEBOOK_USER = 'registerFacebookUser';

    const CACHE_KEY_ACCOUNT_EMAIL = "ACCOUNT.EMAIL";
    const CACHE_KEY_ACCOUNT_USERNAME = "ACCOUNT.USERNAME";

    const TABLE_ACCOUNT = "account";
    const TABLE_FORGOT_PASSWORD = "forgot_password";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var DomainService
     */
    protected $domainService;
    protected $facebookService;

    /**
     * @var EligibilityService
     */
    protected $eligibilityService;

    /**
     * AccountService constructor.
     *
     * @param EntityManager $entityManager
     * @param DomainService $domainService
     * @param FacebookService $facebookService
     */
    public function __construct(EntityManager $entityManager, DomainService $domainService, FacebookService $facebookService)
    {
        $this->em = $entityManager;
        $this->domainService = $domainService;
        $this->facebookService = $facebookService;
        $this->eligibilityService = $eligibilityService = app(\App\Services\EligibilityService::class);;
    }

    /**
     * @return array
     */
    protected function getValidationRules(): array
    {
        return [
            static::VALIDATE_REGISTER => [
                'email'             => 'email|required|unique:App\Entity\Account,email,NULL,account_id,domain,'
                    . \Domain::get()->getId(),
                'firstname'         => ['required','regex:/[a-zA-Z -]+/'],
                'lastname'          => ['required','regex:/[a-zA-Z -]+/'],
                'password'          => 'string|required_without_all:facebookToken|min:6',
                'facebookToken'     => 'required_without_all:password'
            ],
            static::VALIDATE_REGISTER_FACEBOOK_USER => [
                'email'         => 'email|required|unique:App\Entity\Account,email,NULL,account_id,domain,'
                    . \Domain::get()->getId(),
                'first_name'     => ['required','regex:/[a-zA-Z -]+/'],
                'last_name'      => ['required','regex:/[a-zA-Z -]+/'],
                'id'             => 'required|string',
                'link'           => 'required|string'
            ],
            static::VALIDATE_UPDATE   => [
                'password'            => 'string|min:6',
                'firstname'           => ['regex:/[a-zA-Z -]+/'],
                'lastname'            => ['regex:/[a-zA-Z -]+/'],
                'phone'               => 'digits_between:6,10',
                'dateOfBirth'         => 'string',
                'gender'              => 'string',
                'citizenship'         => 'numeric|exists:App\Entity\Citizenship,id',
                'ethnicity'           => 'numeric|exists:App\Entity\Ethnicity,id',
                'country'             => 'numeric|exists:App\Entity\Country,id',
                'state'               => 'numeric|exists:App\Entity\State,id',
                'city'                => 'string',
                'address'             => 'string',
                'zip'                 => 'digits:5',
                'schoolLevel'         => 'numeric|exists:App\Entity\SchoolLevel,id',
                'degree'              => 'numeric|exists:App\Entity\Degree,id',
                'degreeType'          => 'numeric|exists:App\Entity\DegreeType,id',
                'enrollmentYear'      => 'numeric|min:1900',
                'enrollmentMonth'     => 'numeric|between:1,12',
                'gpa'                 => 'string',
                'careerGoal'          => 'numeric|exists:App\Entity\CareerGoal,id',
                'graduationYear'      => 'numeric|min:1900',
                'graduationMonth'     => 'numeric|between:1,12',
                'studyOnline'         => ['regex:/(yes|no|maybe)+/'],
                'pro'                 => 'boolean',
                'highschool'          => 'string',
                'university'          => 'string',
                'university1'         => 'string',
                'university2'         => 'string',
                'university3'         => 'string',
                'university4'         => 'string',
                'enrolled'            => 'boolean',
                'militaryAffiliation' => 'numeric|exists:App\Entity\MilitaryAffiliation,id',
                'recurringApplication' => 'numeric|min:0|max:3'
            ],
        ];
    }


    /**
     * @param string $firstName
     * @param string $lastName
     * @param $email
     * @param $phone
     * @param int $country
     * @param null $password
     * @return Account
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register(string $firstName, string $lastName, $email, $phone, $country = Country::USA, $password = null)
    {
        $country = $country === null ? Country::USA : $country;
        $username = $this->generateUsername($email);
        $password = \Hash::make($password !== null ? $password : PasswordService::generatePassword());
        $account = new Account($email, $username, $password, $this->domainService->get());

        $this->em->persist($account);
        $this->em->flush($account);

        $account->setProfile($profile = new Profile($firstName, $lastName, $country, $phone));

        $this->em->persist($profile);
        $this->em->flush();

        \Event::dispatch(new CreateAccountEvent($account));

        return $account;
    }

    /**
     * Register new Account
     * @param array $data
     * @param string $rules
     * @return Account
     * @throws FacebookResponseException
     */
    public function registerAccount(array $data, $rules = self::VALIDATE_REGISTER)
    {
        $this->validate($data, $this->getValidationRules()[$rules]);

        $account = new Account(
            $data['email'],
            $this->generateUsername($data['email']),
            isset($data['password']) ? \Hash::make($data['password']) : \Hash::make(PasswordService::generatePassword()),
            $this->domainService->get()
        );

        $this->em->persist($account);
        $this->em->flush();

        $profile = new Profile($data['firstname'], $data['lastname'], Country::USA);
        $account->setProfile($profile);

        $this->em->persist($profile);
        $this->em->flush();

        if (isset($data['facebookToken'])) {
            $facebookUser = $this->facebookService->getFacebookGraphUser($data['facebookToken']);
            $account = $this->createSocialEntity($account, $data['facebookToken'], $facebookUser->getId());
        }

        \Event::dispatch(new CreateAccountEvent($account));

        return $account;
    }

    /**
     * @param GraphUser $graphUser
     * @param string $facebookToken
     * @param string $rules
     * @return Account
     */
    public function registerFacebookAccount(GraphUser $graphUser, string $facebookToken, $rules = self::VALIDATE_REGISTER_FACEBOOK_USER)
    {
        $this->validate($graphUser->asArray(), $this->getValidationRules()[$rules]);

        $account = new Account(
            $graphUser->getEmail(),
            $this->generateUsername($graphUser->getEmail()),
            \Hash::make(PasswordService::generatePassword()),
            $this->domainService->get()
        );

        $this->em->persist($account);
        $this->em->flush();

        $profile = new Profile($graphUser->getFirstName(), $graphUser->getLastName(), Country::USA);
        $account->setProfile($profile);

        $this->em->persist($profile);
        $this->em->flush();

        $this->createSocialEntity($account, $facebookToken, $graphUser->getId());

        \Event::dispatch(new CreateAccountEvent($account));

        return $account;
    }

    /**
     * @param Account $account
     * @param array   $data
     *
     * @return Account
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateProfile(Account $account, array $data) : Account
    {
        $this->validate($data, $this->getValidationRules()[self::VALIDATE_UPDATE]);

        if (key_exists('password', $data)) {
            $this->updatePassword($account, $data['password']);
            unset($data['password']);
        }

        if (key_exists('dateOfBirth', $data)) {
            $format = DateService::getFormat($data['dateOfBirth']);
            if ($format != null) {
                $data['dateOfBirth'] = \DateTime::createFromFormat($format, $data['dateOfBirth']);
            }
        }

        if (key_exists('deviceToken', $data)) {
            $account->setDeviceToken($data['deviceToken']);
            unset($data['deviceToken']);
        }

        $account->getProfile()->hydrate($data);

        $this->em->flush();

        // Fire Event
        \Event::dispatch(new UpdateAccountEvent($account));

        return $account;
    }

    /**
     * @param Account $account
     *
     * @return string
     */
    public function generateNewPassword(Account $account)
    {
        $password = PasswordService::generatePassword();

        $this->updatePassword($account, $password);

        return $password;
    }

    /**
     * @param string $password
     * @param Account $account
     */
    public function updatePassword(Account $account, string $password)
    {
        $account->setPassword(\Hash::make($password));
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return string
     */
    public function generateUsername(string $email)
    {
        $username = strstr($email, "@", true);
        $username = preg_replace('/[^a-zA-Z0-9]+/', '', $username);

        $isUsernameTaken = (bool)$this->getRepository()->findOneBy(['username' => $username]);

        if ($isUsernameTaken) {
            $hash = substr(md5(openssl_random_pseudo_bytes(20)), 0, 4);
            $username .= $hash;
        }

        return $username;
    }

    /**
     * @return AccountRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository(Account::class);
    }

    /**
     * @param int $app
     */
    public function setFacebookApp($app = FacebookService::SCHOLARSHIPOWL_APP)
    {
        $this->facebookService->setApp($app);
    }

    /**
     * @param Account $account
     * @param string $facebookToken
     * @param string $id
     * @param string $link
     * @return Account
     */
    public function createSocialEntity(Account $account, string $facebookToken, string $id, string $link = null): Account
    {
        $socialAccount = new SocialAccount($id, $link);
        $socialAccount->setToken($facebookToken);

        $account->setSocialAccount($socialAccount);

        $this->em->persist($socialAccount);
        $this->em->flush();

        return $account;
    }

    /**
     * @param int $accountId
     * @return Account
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteAccount(int $accountId)
    {
        /** @var Account $account */
        $account = $this->getRepository()->findById($accountId);

        $subscriptions = $this->em->getRepository(Subscription::class)->findBy([
            'account' => $accountId,
            'subscriptionStatus' => SubscriptionStatus::ACTIVE
        ]);

        /** @var RemotePaymentManager $pm */
        $pm = app(RemotePaymentManager::class);
        foreach ($subscriptions as $sb) {
            $pm->cancelSubscription($sb);
        }

        $this->em->remove($account);
        $this->em->flush();

        \Event::dispatch(new DeleteAccountEvent($accountId));

        return $account;
    }

    /**
     * Deletes soft-deleted accounts
     *
     * @param int $softDeletedDaysAgo
     * @return array
     * @throws \Exception
     */
    public function hardDeleteAccounts(int $softDeletedDaysAgo = 0): array
    {
        $connection = \DB::connection();

        $sql = "SELECT account_id, email FROM account WHERE DATEDIFF(now(), deleted_at) >= ?";
        $result = $connection->select($connection->raw($sql), [$softDeletedDaysAgo]);
        $ids = array_column($result, 'account_id');
        $cnt = $this->purgeAccounts($ids);

        return [
            'cnt' => $cnt,
            'accounts' => array_combine(array_column($result, 'account_id'), array_column($result, 'email'))
        ];
    }

    /**
     * Hard deletes an accounts
     *
     * @param int accountId
     * @return array
     * @throws \Exception
     */
    public function hardDeleteAccount(int $accountId): array
    {
        $connection = \DB::connection();

        $sql = "SELECT account_id, email FROM account where account_id = ?";
        $result = $connection->select($connection->raw($sql), [$accountId]);

        if ($result) {
            $ids = array_column($result, 'account_id');
            $cnt = $this->purgeAccounts([$ids]);
        }

        return [
            'cnt' => $cnt ?? 0,
            'accounts' => $result ?
                 array_combine(array_column($result, 'account_id'), array_column($result, 'email')) : []
        ];
    }

    /**
     * @param int $accountId
     * @param string $password
     * @return int
     */
    public function changePasswordOld($accountId, $password)
    {
        $result = 0;
        $now = date("Y-m-d H:i:s");

        $password = \Hash::make($password);

        $sql = sprintf("UPDATE %s SET password = ?, last_updated_date = ? WHERE account_id = ?", self::TABLE_ACCOUNT);
        $result = $this->execute($sql, array($password, $now, $accountId));

        $sql = sprintf("DELETE FROM %s WHERE account_id = ?", self::TABLE_FORGOT_PASSWORD);
        $this->execute($sql, array($accountId));

        return $result;
    }

    /**
     * Deletes test accounts
     *
     * @param int $softDeletedDaysAgo
     * @return array
     * @throws \Exception
     */
    public function hardDeleteTestAccounts(int $createdDaysAgo = 0): array
    {
        $connection = \DB::connection();

        $sql = "
            SELECT account_id, email 
            FROM account
            WHERE DATEDIFF(now(), created_date) >= ?
            AND email LIKE 'bot-%'
            AND email LIKE '%@scholarshipowl.com'
        ";
        $result = $connection->select($connection->raw($sql), [$createdDaysAgo]);

        if (count($result) > 9999) {
            throw new \LogicException('Something is wrong, trying to delete too many test-accounts. Check the logic.');
        }

        $ids = array_column($result, 'account_id');
        $cnt = $this->purgeAccounts($ids);

        foreach ($result as $v) {
            \Event::dispatch(new DeleteTestAccountEvent($v->account_id));
        }

        return [
            'cnt' => $cnt,
            'accounts' => array_combine(array_column($result, 'account_id'), array_column($result, 'email'))
        ];
    }

    /**
     * Delete accounts and their's related data
     *
     * @param array $ids
     * @return int Number of accounts deleted
     * @throws \Exception
     */
	protected function purgeAccounts(array $ids): int
	{
	    $result = 0;

	    if (!count($ids)) {
	        return $result;
	    }

        $connection = \DB::connection();
        $connection->beginTransaction();

        // order does matter!!!
		try {
			\DB::table('login_history')->whereIn('account_id', $ids)->delete();
			\DB::table('conversation')->whereIn('account_id', $ids)->delete();
			\DB::table('forgot_password')->whereIn('account_id', $ids)->delete();

            \DB::table('account_login_token')->whereIn('account_id', $ids)->delete();
            \DB::table('account_onboarding_call')->whereIn('account_id', $ids)->delete();
            \DB::table('account_hasoffers_flag')->whereIn('account_id', $ids)->delete();
            \DB::table('account_fresh_scholarship')->whereIn('account_id', $ids)->delete();

			\DB::table('marketing_system_account_data')->whereIn('account_id', $ids)->delete();
			\DB::table('marketing_system_account')->whereIn('account_id', $ids)->delete();
			\DB::table('ab_test_account')->whereIn('account_id', $ids)->delete();

			\DB::table('affiliate_goal_response_data')
                ->whereIn('affiliate_goal_response_id', function(Builder $query) use ($ids) {
                    $query->select('affiliate_goal_response_id')
                      ->from('affiliate_goal_response')
                      ->whereIn('account_id', $ids);
            })->delete();
			\DB::table('affiliate_goal_response')->whereIn('account_id', $ids)->delete();
			\DB::table('submission')->whereIn('account_id', $ids)->delete();
			\DB::table('transactional_email_send')->whereIn('account_id', $ids)->delete();


			\DB::table('address_updated_subscriptions')
                ->whereIn('subscription_id', function(Builder $query) use ($ids) {
                    $query->select('subscription_id')
                      ->from('subscription')
                      ->whereIn('account_id', $ids);
            })->delete();
            \DB::table('transaction')->whereIn('account_id', $ids)->delete();
			\DB::table('application')->whereIn('account_id', $ids)->delete();
            \DB::table('subscription')->whereIn('account_id', $ids)->delete();

			\DB::table('application_essay')->whereIn('account_id', $ids)->delete();
			\DB::table('application_text')->whereIn('account_id', $ids)->delete();
			\DB::table('application_file')->whereIn('account_id', $ids)->delete();
			\DB::table('application_image')->whereIn('account_id', $ids)->delete();
			\DB::table('application_input')->whereIn('account_id', $ids)->delete();
			\DB::table('application_failed_tries')->whereIn('account_id', $ids)->delete();
			\DB::table('winner')->whereIn('account_id', $ids)->delete();

			\DB::table('essay_files')
                ->whereIn('account_file_id', function(Builder $query) use ($ids) {
                    $query->select('id')
                      ->from('account_file')
                      ->whereIn('account_id', $ids);
            })->delete();
			\DB::table('account_file')->whereIn('account_id', $ids)->delete();
			\DB::table('files')->whereIn('account_id', $ids)->delete();

			\DB::table('account_eligible_scholarships_count')->whereIn('account_id', $ids)->delete();


			\DB::table('mission_goal_account')
                ->whereIn('mission_account_id', function(Builder $query) use ($ids) {
                    $query->select('mission_account_id')
                      ->from('mission_account')
                      ->whereIn('account_id', $ids);
            })->delete();
			\DB::table('mission_account')->whereIn('account_id', $ids)->delete();

			\DB::table('referral')
			    ->whereIn('referred_account_id', $ids)
			    ->orWhereIn('referral_account_id', $ids)
			    ->delete();
			\DB::table('referral_award_account')->whereIn('account_id', $ids)->delete();
            \DB::table('affiliate_goal_response')->whereIn('account_id', $ids)->delete();
            \DB::table('referral_share')->whereIn('account_id', $ids)->delete();

			\DB::table('applyme_payments')->whereIn('account_id', $ids)->delete();
			\DB::table('installations')->whereIn('account_id', $ids)->delete();
			\DB::table('log_gts_form_url')->whereIn('account_id', $ids)->delete();
			\DB::table('onesignal_account')->whereIn('account_id', $ids)->delete();
			\DB::table('super_college_scholarship_match')->whereIn('account_id', $ids)->delete();

			\DB::table('social_account')->whereIn('account_id', $ids)->delete();
			\DB::table('profile')->whereIn('account_id', $ids)->delete();


			\DB::table('admin_activity_log')
                ->whereIn('admin_id', function(Builder $query) use ($ids) {
                    $query->select('admin_id')
                      ->from('admin')
                      ->whereIn('account_id', $ids);
            })->delete();
			\DB::table('admin')->whereIn('account_id', $ids)->delete();

			\DB::table('eligibility_cache')->whereIn('account_id', $ids)->delete();

			$result = \DB::table('account')->whereIn('account_id', $ids)->delete();

			$connection->commit();
		}
		catch (\Exception $e) {
			$connection->rollback();
			throw $e;
		}

		return $result;
	}


    /**
     * @param Account $account
     * @param string  $email
     *
     * @return Account
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Illuminate\Validation\ValidationException
     */
	public function changeEmail(Account $account, string $email)
    {
        $this->validate(['email' => $email], [
            'email' => 'email|unique:App\Entity\Account'
        ]);

        if(!empty($email)){
            $account->setEmail($email);
        }

        $this->em->persist($account);
        $this->em->flush();

        return $account;
    }

    /**
     * @param Account $account
     * @param string  $password
     *
     * @return Account
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
	public function changePassword(Account $account, string $password)
    {
        $this->updatePassword($account, $password);
        $this->em->persist($account);
        $this->em->flush();
        return $account;
    }
}
