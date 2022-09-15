<?php namespace App\Entity\Resource;

use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Scholarship;
use App\Entity\SocialAccount;
use App\Entity\Subscription;
use ScholarshipOwl\Data\AbstractResource;
use \Doctrine\Common\Persistence\Proxy;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class AccountResource extends AbstractResource
{
    /** @var Account */
    protected $entity;

    protected $fields = [
        'accountId'     => null,
        'email'         => null,
        'username'      => null,
        'profile'       => ProfileResource::class,
    ];

    /**
     * AccountResource constructor.
     *
     * @param Account $account
     */
    public function __construct(Account $account = null)
    {
        $this->entity = $account;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $profile = new ProfileResource($this->entity->getProfile());

        if ($this->entity->getSocialAccount() !== null) {
            $socialAccount = new SocialAccountResource($this->entity->getSocialAccount());
        }

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = \EntityManager::getRepository(Scholarship::class);

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);
        $subscription = $subscriptionRepository->getTopPrioritySubscription($this->entity);

        $marketingService = new MarketingSystemService();
        $marketingSystemAccount = $marketingService->getMarketingSystemAccount($this->entity->getAccountId());

        $username = $this->entity->getUsername();

        return [
            'accountId'             => $this->entity->getAccountId(),
            'email'                 => $this->entity->getEmail(),
            'isMember'              => $this->entity->isMember(),
            'isFreemium'            => $this->entity->isFreemium(),
            'credits'               => $this->entity->getCredits(),
            'freemiumCredits'       => $this->entity->getFreemiumCredits(),
            'membership'            => str_replace(' Membership', '', $subscription ? $subscription->getName() : 'Free'),
            'freeTrial'             => $subscription && $subscription->isFreeTrial(),
            'packagePrice'          => $subscription ? $subscription->getPrice() : 0,
            'freeTrialEndDate'      => $subscription && $subscription->getFreeTrialEndDate() ?
                $subscription->getFreeTrialEndDate()->format('M j, Y') : null,
            'eligibleScholarships'  => $scholarshipRepository->countEligibleScholarships($this->entity),
            'username'              => $this->entity->getUsername(),
            'socialAccount'         => isset($socialAccount) ?  $socialAccount->toArray() : null,
            'marketing'             => [
                'affiliateId'       => (int) $marketingSystemAccount->getHasOffersAffiliateId(),
                'offerId'           => (int) $marketingSystemAccount->getHasOffersOfferId(),
                'transactionId'     => $marketingSystemAccount->getHasOffersTransactionId(),
            ],
            'profile'               => $profile->toArray()
        ];
    }
}
