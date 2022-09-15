<?php namespace App\Jobs;

use App\Entity\Account;

use App\Entity\Repository\AccountRepository;
use App\Entity\SubscriptionAcquiredType;
use App\Events\Account\UpdateAccountEvent;
use App\Services\PaymentManager;
use Doctrine\ORM\EntityManager;
use ScholarshipOwl\Data\Service\Account\ReferralAwardAccountService;
use ScholarshipOwl\Data\Service\Account\ReferralAwardService;
use ScholarshipOwl\Data\Service\Account\ReferralService;
use ScholarshipOwl\Data\Service\Mission\MissionAccountService;

class ReferralRewardJob extends Job
{
    /**
     * @var string
     */
    protected $referralCode;

    /**
     * @var int
     */
    protected $accountId;

    /**
     * @var string
     */
    protected $ch;

    /**
     * @param $accountId
     * @param $referralCode
     * @param $ch
     *
     * @return mixed
     */
    public static function dispatch($accountId, $referralCode, $ch)
    {
        return dispatch(new static($accountId, $referralCode, $ch));
    }

    /**
     * ReferralRewardJob constructor.
     *
     * @param $accountId
     * @param $referralCode
     * @param $ch
     */
    public function __construct($accountId, $referralCode, $ch)
    {
        $this->accountId = $accountId;
        $this->referralCode = $referralCode;
        $this->ch = $ch;
    }

    /**
     * @param EntityManager  $em
     * @param PaymentManager $pm
     *
     * @throws \Exception
     */
    public function handle(EntityManager $em, PaymentManager $pm)
    {
        /** @var AccountRepository $accountRepository */
        $accountRepository = $em->getRepository(Account::class);
        $referralService = new ReferralService();

        if ($referralAccount = $accountRepository->findByReferralCode($this->referralCode)) {
            $referralService->saveReferral($this->accountId, $referralAccount->getAccountId(), $this->ch);
        }

        //  Award automatic referral packages
        $referralAwardsService = new ReferralAwardService();
        $referralAwards = $referralAwardsService->getReferralAwards(true);

        if(count($referralAwards)) {
            /** @var Account $account */
            $account = $accountRepository->find($this->accountId);
            $referralAwardAccountService = new ReferralAwardAccountService();

            foreach($referralAwards as $referralAward){
                if($packageId = $referralAward->getReferralPackage()->getPackageId()) {
                    $pm->applyPackageOnAccount($account, $packageId, SubscriptionAcquiredType::REFERRAL);
                    $referralAwardAccountService
                        ->saveReferralAwardAccount($this->accountId, $referralAward->getReferralAwardId());
                }
            }
        }

        $missionAccountService = new MissionAccountService();
        $missionAccountService->completeReferAFriendGoals($referralAccount->getAccountId());
    }
}
