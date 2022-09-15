<?php

namespace App\Services\PubSub;

use App\Contracts\DictionaryContract;
use App\Entity\Account;
use App\Entity\Log\LoginHistory;
use App\Entity\Package;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Entity\SubscriptionAcquiredType;
use App\Entity\SubscriptionStatus;
use App\Services\Account\AccountLoginTokenService;
use App\Services\EligibilityCacheService;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxService;
use App\Services\ScholarshipService;
use App\Services\SubscriptionService;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Google\Cloud\PubSub\PubSubClient;
use ScholarshipOwl\Data\DateHelper;
use ScholarshipOwl\Data\Service\Account\ReferralService;

class AccountService extends AbstractPubSubService
{
    const PUBSUB_TOPIC = 'sowl.user';

    /**
     * Account tags
     */
    const FIELD_EMAIL = 'email';
    const FIELD_ACCOUNT_ID = 'account_id';
    const FIELD_LOGIN_TOKEN = 'login_token';
    const FIELD_REFERRED_BY = 'referred_by';
    const FIELD_REFERRAL_CODE = 'referral_code';
    const FIELD_REGISTERED_AT = 'registered_at';

    /**
     * Profile data tags
     */
    const FIELD_FIRST_NAME = 'first_name';
    const FIELD_LAST_NAME = 'last_name';
    const FIELD_PROFILE_COMPLETENESS = 'profile_completeness';
    const FIELD_SCHOOL_LEVEL = 'school_level';
    const FIELD_DATE_OF_BIRTH = 'date_of_birth';
    const FIELD_STATE = 'state';
    const FIELD_MAJOR = 'major';
    const FIELD_DEGREE_TYPE = 'degree_type';
    const FIELD_ENROLLMENT_DATE = 'enrolment_date';
    const FIELD_CAREER_GOAL = 'career_goal';
    const FIELD_HIGHSCHOOL = 'school';
    const FIELD_UNIVERSITY = 'university';
    const FIELD_GENDER = 'gender';
    const FIELD_GPA = 'gpa';
    const FIELD_HIGHSCHOOL_GRADUATION_DATE = 'hs_grad_date';
    const FIELD_COLLEGE_GRADUATION_DATE = 'college_grad_date';

    /**
     * Memberships and packages
     */
    const FIELD_TRIAL = 'package_is_trial';
    const FIELD_PACKAGE = 'package_name';
    const FIELD_PACKAGE_PRICE = 'package_price';
    const FIELD_PACKAGE_RENEWAL_FREQUENCY = 'package_renewal_frequency';
    const FIELD_PACKAGE_RENEWAL_DATE = 'package_renewal_date';
    const FIELD_MEMBERSHIP_STATUS = 'membership_status';
    const FIELD_SUBSCRIPTION_IS_PAID = 'subscription_is_paid';

    /**
     * Scholarships data
     */
    const FIELD_SCHOLARSHIP_EL_COUNT = 'scholarship_eligible_count';
    const FIELD_SCHOLARSHIP_EL_COUNT_NEW = 'scholarship_new_count';
    const FIELD_SCHOLARSHIP_EL_COUNT_EXPIRING = 'scholarship_expiring_count';
    const FIELD_SCHOLARSHIP_EL_AMOUNT = 'scholarship_eligible_amount';
    const FIELD_SCHOLARSHIP_EL_AMOUNT_NEW = 'scholarship_new_amount';
    const FIELD_SCHOLARSHIP_EL_AMOUNT_EXPIRING = 'scholarship_expiring_amount';
    const FIELD_SCHOLARSHIP_EL_LIST_EXPIRING = 'scholarship_expiring_list';

    /**
     * Misc
     */
    const FIELD_UNREAD_MESSAGES_COUNT = 'unread_messages_count';
    const FIELD_UNREAD_MESSAGES_LIST = 'unread_messages_list';
    const FIELD_FSET = 'feature_set';
    const FIELD_SRV = 'srv';

    /**
     * @var PubSubClient
     */
    protected $pubSubClient;

    /**
     * @var ScholarshipService
     */
    protected $ss;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;

    /**
     * @var MailboxService
     */
    protected $mailboxService;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityManager
     */
    protected $mem;

    /**
     * @var AccountLoginTokenService
     */
    protected $loginTokenService;

    /**
     * @var bool
     */
    protected $regenerateLoginToken = false;

    /**
     * @param ManagerRegistry $mr
     * @param ScholarshipService $ss
     * @param AccountLoginTokenService $loginTokenService
     * @throws \Exception
     */
    public function __construct(
        PubSubClient $pubSubClient,
        ManagerRegistry $mr,
        ScholarshipService $ss,
        AccountLoginTokenService $loginTokenService,
        EligibilityCacheService $elbCacheService,
        MailboxService $mailboxService
    )
    {
        $this->pubSubClient = $pubSubClient;
        $this->ss = $ss;
        $this->em = $mr->getManager($mr->getDefaultManagerName());
        $this->loginTokenService = $loginTokenService;
        $this->elbCacheService = $elbCacheService;
        $this->mailboxService = $mailboxService;
    }

    /**
     * @return PubSubClient
     */
    public function getPubSubClient()
    {
        return $this->pubSubClient;
    }

    /**
     * @param PubSubClient $client
     * @return $this
     */
    public function setPubSubClient(PubSubClient $client)
    {
        $this->pubSubClient = $client;

        return $this;
    }

    /**
     * @return bool
     */
    public function getRegenerateLoginToken()
    {
        return $this->regenerateLoginToken;
    }

    /**
     * @param bool $val
     * @return $this
     */
    public function setRegenerateLoginToken(bool $val)
    {
        $this->regenerateLoginToken = $val;

        return $this;
    }

    /**
     * Push a create/update account message
     *
     * @param Account $account
     * @param bool $isUpdate
     * @return array
     */
    public function addOrUpdateAccount(Account $account, $isUpdate = true): array
    {
        $accountId = $account->getAccountId();
        $fields = $this->populateMergeFields([$account])[$accountId];

        $this->publishMessage(
            json_encode($fields),
            [
                'action' => $isUpdate ? 'update' : 'create',
                'accountId' => (string)$account->getAccountId()
            ]
        );

        return $fields;
    }

    /**
     * Push an update account message, partial update
     *
     * @param Account $account
     * @param array $targetFields
     * @return array
     */
    public function updateAccount(Account $account, array $targetFields): array
    {
        $accountId = $account->getAccountId();
        $fields = $this->populateMergeFields([$account], $targetFields)[$accountId];

        $accountId = $account->getAccountId();

        $this->publishMessage(
            json_encode($fields),
            [
                'action' => 'update',
                'accountId' => (string)$account->getAccountId()
            ]
        );

        return $fields;
    }

    /**
     * Push a delete account message
     *
     * @param Account $account
     */
    public function deleteAccount(int $accountId)
    {
        $this->publishMessage(
            json_encode(['account_id' => $accountId]),
            [
                'action' => 'delete',
                'accountId' => (string)$accountId
            ]
        );
    }

    /**
     * List of all merge fields
     *
     * @return array
     * @throws \ReflectionException
     */
    public static function fields(): array
    {
        $fields = array_filter((new \ReflectionClass(__CLASS__))->getConstants(), function ($key) {
            return strpos($key, 'FIELD_') !== false;
        }, ARRAY_FILTER_USE_KEY);

        return $fields;
    }

    /**
     * publish message to PubSub
     *
     * @param string|null $data
     * @param array $attributes
     */
    protected function publishMessage(string $data = null, array $attributes = [])
    {
        $topic = $this->pubSubClient->topic(self::PUBSUB_TOPIC);

        $topic->publish([
            'data' => $data,
            'attributes' => $attributes
        ]);

        \Log::debug(
            "Account add/update message sent to PubSub, data: {$data} attributes: ".json_encode($attributes)
        );
    }

    /**
     * Populates specified merge fields for specified accounts.
     * Id no target fields specified then all merge fields will be populated.
     *
     * @param array $accounts
     * @param array|null $targetFields
     * @return array Merge fields indexed by accountId
     */
    public function populateMergeFields(array $accounts, array $targetFields = null): array
    {
        $targetFields = $targetFields ?? array_values(self::fields());

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->em->getRepository(Subscription::class);

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em->getRepository(Scholarship::class);

        $referredBy = [];
        $elbCacheItems = null;
        $scholarshipElIds = [];
        $scholarshipElAmount = [];
        $scholarshipElNewAmount = [];
        $scholarshipElCount = [];
        $scholarshipExpiringIds = [];
        $scholarshipElExpiringCount = [];
        $scholarshipElExpiringAmount = [];
        $scholarshipNewIds = [];
        $scholarshipElNewCount = [];
        $countEmails = [];
        $listUnreadMessages = [];
        $activeSubscriptions = [];
        $lastSubscriptions = [];
        $accountLoginTokens = [];
        $fSetAndSrv = [];

        $ids = self::pluckAccountId($accounts);
        $batchCount = count($ids);
        $usernameList = self::pluckUsername($accounts);
        $mergeFields = [];

        /** @var Account $account */
        foreach ($accounts as $account) {
            $accountId = $account->getAccountId();
            $username = $account->getUsername();
            $mailbox = strtolower($username);
            $profile = $account->getProfile();

            if (in_array(self::FIELD_EMAIL, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_EMAIL] = $account->getEmail();
            }

            if (in_array(self::FIELD_ACCOUNT_ID, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_ACCOUNT_ID] = $account->getAccountId();
            }

            if (in_array(self::FIELD_REGISTERED_AT, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_REGISTERED_AT] = $account->getCreatedDate()->format('Y-m-d H:i:s.u T');
            }

            if (in_array(self::FIELD_REFERRED_BY, $targetFields)) {
                if (!$referredBy) {
                    $referredBy = $this->getReferredBy($ids) ?? '';
                }
                $mergeFields[$accountId][self::FIELD_REFERRED_BY] = $referredBy[$accountId] ?? '';
            }

            if (in_array(self::FIELD_REFERRAL_CODE, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_REFERRAL_CODE] = $account->getReferralCode() ?? '';
            }

            if (in_array(self::FIELD_LOGIN_TOKEN, $targetFields)) {
                if ($this->getRegenerateLoginToken()) {
                    if (!$accountLoginTokens) {
                        $accountLoginTokens = $this->loginTokenService->generateTokens($accounts);
                    }

                    $mergeFields[$accountId][self::FIELD_LOGIN_TOKEN] = $accountLoginTokens[$accountId];
                } else {
                    $mergeFields[$accountId][self::FIELD_LOGIN_TOKEN] =
                        $this->loginTokenService->getLatestToken($account)->getToken();
                }
            }

            if (in_array(self::FIELD_FIRST_NAME, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_FIRST_NAME] = $profile->getFirstName() ?? '';
            }

            if (in_array(self::FIELD_LAST_NAME, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_LAST_NAME] = $profile->getLastName() ?? '';
            }

            if (in_array(self::FIELD_GPA, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_GPA] = (float)$profile->getGpa() ?? '';
            }

            if (in_array(self::FIELD_PROFILE_COMPLETENESS, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_PROFILE_COMPLETENESS] =
                    $profile->getCompleteness() ?? '';
            }

            if (in_array(self::FIELD_SCHOOL_LEVEL, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_SCHOOL_LEVEL] =
                    $this->dict($profile->getSchoolLevel()) ?? '';
            }

            if (in_array(self::FIELD_DATE_OF_BIRTH, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_DATE_OF_BIRTH] = $profile->getDateOfBirth() ?
                    $profile->getDateOfBirth()->format(DateHelper::DEFAULT_DATE_FORMAT) : '';
            }

            if (in_array(self::FIELD_STATE, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_STATE] = $this->dict($profile->getState()) ?? '';
            }

            if (in_array(self::FIELD_MAJOR, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_MAJOR] = $this->dict($profile->getDegree()) ?? '';
            }

            if (in_array(self::FIELD_DEGREE_TYPE, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_DEGREE_TYPE] =
                    $this->dict($profile->getDegreeType()) ?? '';
            }

            if (in_array(self::FIELD_ENROLLMENT_DATE, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_ENROLLMENT_DATE] = $profile->getEnrollmentDate() ?? '';
            }

            if (in_array(self::FIELD_COLLEGE_GRADUATION_DATE, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_COLLEGE_GRADUATION_DATE] = $profile->getCollegeGraduationDate() ?? '';
            }

            if (in_array(self::FIELD_HIGHSCHOOL_GRADUATION_DATE, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_HIGHSCHOOL_GRADUATION_DATE] = $profile->getHighschoolGraduationDate() ?? '';
            }

            if (in_array(self::FIELD_CAREER_GOAL, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_CAREER_GOAL] =
                    $this->dict($profile->getCareerGoal()) ?? '';
            }

            if (in_array(self::FIELD_HIGHSCHOOL, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_HIGHSCHOOL] = $profile->getHighschool() ?? '';
            }

            if (in_array(self::FIELD_UNIVERSITY, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_UNIVERSITY] = $profile->getUniversity() ?? '';
            }

            if (in_array(self::FIELD_GENDER, $targetFields)) {
                $mergeFields[$accountId][self::FIELD_GENDER] = $profile->getGender() ?? '';
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_AMOUNT_EXPIRING, $targetFields)) {
                if (!$scholarshipElExpiringAmount) {
                    if (is_null($elbCacheItems) && $batchCount > 1) { // if it's a single account elb might be fetched from cache
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    if (!$scholarshipExpiringIds) {
                        $scholarshipExpiringIds = $this->getExpiringScholarshipIds();
                    }
                    $scholarshipElExpiringAmount = $this->elbCacheService->getEligibleAmount($ids, $scholarshipExpiringIds, $elbCacheItems);
                }
                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_AMOUNT_EXPIRING] = number_format((int)($scholarshipElExpiringAmount[$accountId] ?? 0), 0, '.', ',');
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_AMOUNT_NEW, $targetFields)) {
                if (!$scholarshipElNewAmount) {
                    if (is_null($elbCacheItems) && $batchCount > 1) {
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    if (!$scholarshipNewIds) {
                        $scholarshipNewIds = $scholarshipRepository->findNewScholarshipIds();
                    }
                    $scholarshipElNewAmount = $this->elbCacheService->getEligibleAmount($ids, $scholarshipNewIds, $elbCacheItems);
                }
                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_AMOUNT_NEW] = number_format((int)($scholarshipElNewAmount[$accountId] ?? 0), 0, '.', ',');
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_AMOUNT, $targetFields)) {
                if (!$scholarshipElAmount) {
                    if (is_null($elbCacheItems) && $batchCount > 1) {
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    $scholarshipElAmount = $this->elbCacheService->getEligibleAmount($ids, [], $elbCacheItems);
                }
                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_AMOUNT] = number_format((int)($scholarshipElAmount[$accountId] ?? 0), 0, '.', ',');
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_COUNT, $targetFields)) {
                if (!$scholarshipElCount) {
                    if (is_null($elbCacheItems) && $batchCount > 1) {
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    $scholarshipElCount = $this->elbCacheService->getEligibleCount($ids, [], $elbCacheItems);
                }
                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_COUNT] = $scholarshipElCount[$accountId] ?? 0;
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_COUNT_EXPIRING, $targetFields)) {
                if (!$scholarshipExpiringIds) {
                    $scholarshipExpiringIds = $this->getExpiringScholarshipIds();
                }
                if (!$scholarshipElExpiringCount) {
                    if (is_null($elbCacheItems) && $batchCount > 1) {
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    $scholarshipElExpiringCount = $this->elbCacheService->getEligibleCount($ids, $scholarshipExpiringIds, $elbCacheItems);
                }
                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_COUNT_EXPIRING] = $scholarshipElExpiringCount[$accountId] ?? 0;
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_COUNT_NEW, $targetFields)) {
                if (!$scholarshipElNewCount) {
                    if (is_null($elbCacheItems) && $batchCount > 1) {
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    if (!$scholarshipNewIds) {
                        $scholarshipNewIds = $scholarshipRepository->findNewScholarshipIds();
                    }
                    $scholarshipElNewCount = $this->elbCacheService->getEligibleCount($ids, $scholarshipNewIds, $elbCacheItems);
                }

                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_COUNT_NEW] =
                    $scholarshipElNewCount[$accountId] ?? 0;
            }

            if (in_array(self::FIELD_SCHOLARSHIP_EL_LIST_EXPIRING, $targetFields)) {
                if (!$scholarshipElIds) {
                    if (is_null($elbCacheItems) && $batchCount > 1) {
                        $elbCacheItems = $this->elbCacheService->fetchEligibilityCacheItems($ids, true);
                    }
                    $scholarshipElIds = $this->elbCacheService->getEligibleScholarshipIds($ids, [], $elbCacheItems);
                }
                $expiringFormattedList = $this->getExpiringElScholarshipFormattedList($ids, $scholarshipElIds);

                $mergeFields[$accountId][self::FIELD_SCHOLARSHIP_EL_LIST_EXPIRING] = isset($expiringFormattedList[$accountId]) ?
                    implode("\n", $expiringFormattedList[$accountId]) : '';
            }

            if (in_array(self::FIELD_UNREAD_MESSAGES_COUNT, $targetFields)) {
                if (!$countEmails) {
                    $countEmails = $this->mailboxService->countMultiple($usernameList)->getData();
                }
                /** @var EmailCount $emailCount */
                $emailCount = $countEmails[$mailbox];
                $mergeFields[$accountId][self::FIELD_UNREAD_MESSAGES_COUNT] = $emailCount->getInboxUnread();
            }

            if (in_array(self::FIELD_UNREAD_MESSAGES_LIST, $targetFields)) {
                if (!$listUnreadMessages) {
                    $listUnreadMessages = $this->mailboxService->getUnreadMessagesList($usernameList);
                }
                $mergeFields[$accountId][self::FIELD_UNREAD_MESSAGES_LIST] = $listUnreadMessages[$mailbox];
            }

            if (in_array(self::FIELD_SUBSCRIPTION_IS_PAID, $targetFields)) {
                if (!$activeSubscriptions) {
                    $activeSubscriptions = $subscriptionRepository->findActiveSubscriptions($ids);
                }
                $mergeFields[$accountId][self::FIELD_SUBSCRIPTION_IS_PAID] = $this->isPaid($activeSubscriptions[$accountId]);
            }

            if (in_array(self::FIELD_TRIAL, $targetFields)) {
                if (!$activeSubscriptions) {
                    $activeSubscriptions = $subscriptionRepository->findActiveSubscriptions($ids);
                }

                $mergeFields[$accountId][self::FIELD_TRIAL] = $this->isFreeTrial($activeSubscriptions[$accountId]);
            }

            if (in_array(self::FIELD_PACKAGE_RENEWAL_DATE, $targetFields)) {
                if (!$activeSubscriptions) {
                    $activeSubscriptions = $subscriptionRepository->findActiveSubscriptions($ids);
                }

                $renewalDate = '';
                if (isset($activeSubscriptions[$accountId])) {
                    $renewalDate = $this->getRenewalDate($activeSubscriptions[$accountId]);
                }

                $mergeFields[$accountId][self::FIELD_PACKAGE_RENEWAL_DATE] = $renewalDate;
            }

            if (in_array(self::FIELD_PACKAGE, $targetFields)) {
                if (!$activeSubscriptions) {
                    $activeSubscriptions = $subscriptionRepository->findActiveSubscriptions($ids);
                }

                $package = '';
                if (isset($activeSubscriptions[$accountId])) {
                    $package = $activeSubscriptions[$accountId]->getName();
                }

                $mergeFields[$accountId][self::FIELD_PACKAGE] = $package;
            }

            if (in_array(self::FIELD_PACKAGE_PRICE, $targetFields)) {
                if (!$activeSubscriptions) {
                    $activeSubscriptions = $subscriptionRepository->findActiveSubscriptions($ids);
                }

                $price = 0;
                if (isset($activeSubscriptions[$accountId])) {
                    $price = $activeSubscriptions[$accountId]->getPrice();
                }

                $mergeFields[$accountId][self::FIELD_PACKAGE_PRICE] = $price;
            }

            if (in_array(self::FIELD_PACKAGE_RENEWAL_FREQUENCY, $targetFields)) {
                if (!$activeSubscriptions) {
                    $activeSubscriptions = $subscriptionRepository->findActiveSubscriptions($ids);
                }

                $frequencyDays = 0;
                if (isset($activeSubscriptions[$accountId])) {
                    $subscription = $activeSubscriptions[$accountId];
                    $frequencyDays =  SubscriptionService::calcFrequencyDays($subscription);
                }

                $mergeFields[$accountId][self::FIELD_PACKAGE_RENEWAL_FREQUENCY] = $frequencyDays;
            }

            if (in_array(self::FIELD_MEMBERSHIP_STATUS, $targetFields)) {
                if (!$lastSubscriptions) {
                    $lastSubscriptions = $subscriptionRepository->getLastSubscriptions($ids);
                }
                $mergeFields[$accountId][self::FIELD_MEMBERSHIP_STATUS] =
                    $this->getMembershipStatus($lastSubscriptions[$accountId]);
            }

            if (in_array(self::FIELD_FSET, $targetFields)) {
                if (!$fSetAndSrv) {
                    $fSetAndSrv = $this->getFeatureSetAndSrv($ids);
                }
                $mergeFields[$accountId][self::FIELD_FSET] = $fSetAndSrv[$accountId]['fset'] ?? '';
            }

            if (in_array(self::FIELD_SRV, $targetFields)) {
                if (!$fSetAndSrv) {
                    $fSetAndSrv = $this->getFeatureSetAndSrv($ids);
                }

                $mergeFields[$accountId][self::FIELD_SRV] = $fSetAndSrv[$accountId]['srv'] ?? '';
            }
        }

        return $mergeFields;
    }

    /**
     * @return array|string[]
     */
    private function getExpiringElScholarshipFormattedList($accountIds, array $eligibleScholarshipIds = null)
    {
        $cacheKey = 'account_service_expiring_sch_list';

        $listAll = \Cache::get($cacheKey);
        if (!$listAll) {
            $listAll = $this->ss->scholarshipListData($this->getExpiringScholarshipIds());
            // there is no reliable way to invalidate this cache, so store it for 30 min
            \Cache::put($cacheKey, $listAll, 60 * 30);
        }

        // remove redundant data
        foreach($listAll as $scholarshipId => $data) {
            $listAll[$scholarshipId] = $data['headline'];
        }

        if (is_null($eligibleScholarshipIds)) {
            $eligibleScholarshipIds = $this->elbCacheService->getEligibleScholarshipIds($accountIds, [], null, true);
        }

        $result = [];
        foreach ($eligibleScholarshipIds as $accountId => $scholarshipIds) {
            $targetData =  array_intersect_key($listAll, array_flip($scholarshipIds));
            $result[$accountId] = array_intersect_key($listAll, array_flip($scholarshipIds));
        }

        return $result;
    }

    /**
     * @return array|int[]
     */
    private function getExpiringScholarshipIds()
    {
        $cacheKey = 'account_service_expiring_sch_ids';
        $expiringScholarshipIds = \Cache::get($cacheKey);
        if (!$expiringScholarshipIds) {
            /** @var ScholarshipRepository $scholarshipRepository */
            $scholarshipRepository = $this->em->getRepository(Scholarship::class);
            $expiringScholarshipIds = $scholarshipRepository->findExpiringScholarshipsIds();
            // there is no reliable way to invalidate this cache, so store it for 30 min
            \Cache::put($cacheKey, $expiringScholarshipIds, 60 * 30);
        }

        return $expiringScholarshipIds;
    }

    /**
     * Feature Set for one or many accounts.
     *
     * @param int|array $ids
     * @return array Array indexed by account_id
     */
    private function getFeatureSetAndSrv($ids)
    {
        // select the latest record for each user
        $loginHistory = $this->em->createQueryBuilder()
            ->select(['t1.featureSet', 't1.srv', 'IDENTITY(t1.account) as accountId'])
            ->from(LoginHistory::class, 't1')
            ->leftJoin(
                LoginHistory::class, 't2', 'WITH',
                't1.account = t2.account and t1.loginHistoryId < t2.loginHistoryId'
            )
            ->where('t2.account IS NULL')
            ->andwhere('t1.account IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->setHint(Query::HINT_REFRESH, true)
            ->getResult();

        $data = [];
        foreach ($loginHistory as $item) {
            $data[$item['accountId']]['fset'] = $item['featureSet'];
            $data[$item['accountId']]['srv'] = $item['srv'];
        }

        return $data;
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    private function getReferredBy(array $ids)
    {
        $result = [];
        foreach ($ids as $id) $result[$id] = null;

        $service = new ReferralService();
        $referrals = $service->getReferredAccountsByReferralIds($ids);

        foreach ($referrals as $accountId => $referral) {
            $result[$accountId] = $referral->getProfile()->getFullName();
        }

        return $result;
    }

    /**
     * @param DictionaryContract|null $dictionary
     *
     * @return null|string
     */
    private function dict(DictionaryContract $dictionary = null)
    {
        return $dictionary ? $dictionary->getName() : null;
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return string
     */
    private function isPaid(Subscription $subscription = null)
    {
        $isPaidAndStillActive = $subscription &&
             $subscription->getTransactions()->count() > 0 &&
             ($subscription->isActive() || $subscription->getActiveUntil() > new \DateTime());

        return  $isPaidAndStillActive ? 'Yes' : 'No';
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return string
     */
    private function isFreeTrial(Subscription $subscription = null)
    {
        return $subscription && $subscription->getFreeTrial() ?
            'Yes' : 'No';
    }

    /**
     * @param Subscription|null $subscription
     *
     * @return null|string
     */
    private function getRenewalDate(Subscription $subscription = null)
    {
        if ($subscription && $subscription->isRecurrent()) {
            return $subscription->getRenewalDate()->format(DateHelper::DEFAULT_DATE_FORMAT);
        }

        return null;
    }

    /**
     * Extracts account ids form an array of Accounts
     *
     * @param Account[] $accounts
     * @return array
     */
    private static function pluckAccountId(array $accounts): array
    {
        if (isset($accounts[0])) {
            $ids = [];
            foreach ($accounts as $account) {
                $ids[] = $account->getAccountId();
            }
        } else {
            $ids = array_keys($accounts);
        }

        return $ids;
    }

    /**
     * Extracts account ids form an array of Accounts
     *
     * @param Account[] $accounts
     * @return array
     */
    private static function pluckUsername(array $accounts): array
    {
        $username = [];

        foreach ($accounts as $account) {
            $username[] = $account->getUsername();
        }

        return $username;
    }

    /**
     * Note: Populate with data from last active & highest priority membership;
     * None, Trial Active, Trial Expired, Active, Past due Active, Past due expired, Cancelled Active, Cancelled Expired
     *
     * @param Subscription|null $subscription
     *
     * @return string
     */
    private function getMembershipStatus(Subscription $subscription = null)
    {
        if ($subscription) {
            switch ($subscription->getSubscriptionStatus()->getId()) {
                case SubscriptionStatus::ACTIVE:
                    return $subscription->isFreeTrial() ? 'Trial Active' : 'Active';
                case SubscriptionStatus::CANCELED:
                    return 'Cancelled';
                case SubscriptionStatus::EXPIRED:
                    return 'Expired';
            }
        }

        return 'None';
    }
}
