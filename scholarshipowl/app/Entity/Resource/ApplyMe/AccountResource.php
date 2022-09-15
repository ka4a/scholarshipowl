<?php namespace App\Entity\Resource\ApplyMe;

use App\Entity\Account;
use App\Entity\ApplyMe\ApplymePayments;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Resource\SocialAccountResource;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxService;
use ScholarshipOwl\Data\AbstractResource;
use ScholarshipOwl\Data\ResourceCollection;

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
        $transactions = \EntityManager::getRepository(ApplymePayments::class)->findBy(['account' => $this->entity->getAccountId()]);

        if ($this->entity->getSocialAccount() !== null) {
            $socialAccount = new SocialAccountResource($this->entity->getSocialAccount());
        }
        /** @var MailboxService $mailboxService */
        $mailboxService = app(MailboxService::class);
        /** @var EmailCount $mailboxData */
        $mailboxData = $mailboxService->countEmails($this->entity)->getData();

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = \EntityManager::getRepository(Scholarship::class);

        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);
        $subscription = $subscriptionRepository->getTopPrioritySubscription($this->entity);
        $username = $this->entity->getUsername();

        $data = [
            'accountId'            => $this->entity->getAccountId(),
            'email'                => $this->entity->getEmail(),
            'isMember'             => $this->entity->isMember(),
            'membership'           => str_replace(' Membership', '', $subscription ? $subscription->getName() : 'Free'),
            'freeTrial'            => $subscription && $subscription->isFreeTrial(),
            'freeTrialEndDate'     => $subscription && $subscription->getFreeTrialEndDate() ?
                $subscription->getFreeTrialEndDate()->format('M j, Y') : null,
            'eligibleScholarships' => $scholarshipRepository->countEligibleScholarships($this->entity),
            'unreadInbox'          => $mailboxData->getInboxUnread(),
            'username'             => $this->entity->getUsername(),
            'socialAccount'        => isset($socialAccount) ?  $socialAccount->toArray() : null,
            'transactions'         => null,
            'profile'              => $profile->toArray()
        ];

        if (count($transactions)) {
            $data['transactions'] = ResourceCollection::collectionToArray(
                new PaymentsResource(),
                $transactions
            );
        }

        return $data;
    }
}
