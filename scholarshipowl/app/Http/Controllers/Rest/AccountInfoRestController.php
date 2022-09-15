<?php

namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Repository\ApplicationRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Repository\SubscriptionRepository;
use App\Entity\Resource\CoregsResource;
use App\Entity\Resource\ProfileResource;
use App\Entity\Resource\SocialAccountResource;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Extensions\GenericResponse;
use App\Http\Traits\JsonResponses;
use App\Services\EligibilityCacheService;
use App\Services\Mailbox\EmailCount;
use App\Services\Mailbox\MailboxService;
use App\Services\ScholarshipService;
use Doctrine\ORM\EntityManager;
use Illuminate\Routing\Controller;
use ScholarshipOwl\Data\Service\Marketing\MarketingSystemService;

class AccountInfoRestController extends Controller
{
    use JsonResponses;

    /**
     * @var Account
     */
    protected $account;

    public function getData($fields = null)
    {
        $this->account = \Auth::user();
        $data = [];

        if ($fields = request()->get('fields')) {
            $fields = str_word_count($fields, 1);
        }

        if (($fields && in_array('scholarship', $fields)) || !$fields) {
            $this->populateScholarship($data);
        }

        if (($fields && in_array('application', $fields)) || !$fields) {
            $this->populateApplication($data);
        }

        if (($fields && in_array('mailbox', $fields)) || !$fields) {
            $this->populateMailbox($data);
        }

        if (($fields && in_array('account', $fields)) || !$fields) {
            $this->populateAccount($data);
        }

        if (($fields && in_array('profile', $fields)) || !$fields) {
            $this->populateProfile($data);
        }

        if (($fields && in_array('socialAccount', $fields)) || !$fields) {
            $this->populateSocialAccount($data);
        }

        if (($fields && in_array('marketing', $fields)) || !$fields) {
            $this->populateMarketing($data);
        }

        if (($fields && in_array('membership', $fields)) || !$fields) {
            $this->populateMembership($data);
        }

        return $this->jsonSuccessResponse($data);
    }

    protected function populateScholarship(&$data)
    {
        /** @var EligibilityCacheService $elbCacheService */
        $elbCacheService = app()->get(EligibilityCacheService::class);
        $accountId = $this->account->getAccountId();
        $data['scholarship']['eligibleCount'] = $elbCacheService->getAccountEligibleCount($accountId);
        $data['scholarship']['eligibleAmount'] = $elbCacheService->getAccountEligibleAmount($accountId);
    }

    protected function populateApplication(&$data)
    {
        /** @var ApplicationRepository $repo */
        $repo = \EntityManager::getRepository(Application::class);

        $data['application']['total'] = $repo->countApplications($this->account);
    }

    protected function populateMailbox(&$data)
    {
        /** @var MailboxService $service */
        $service = app(MailboxService::class);

        try {
            /** @var GenericResponse $result */
            $result = $service->countEmails();
            /** @var EmailCount $mailboxData */
            $mailboxData = $result->getData();
            $data['mailbox'] = $mailboxData->jsonSerialize();
            $data['mailbox']['error'] = $result->getError();
        } catch (\Exception $e) {
            \Log::error($e);
            $data['mailbox'] = EmailCount::populate([])->jsonSerialize();
            $data['mailbox']['error'] = $e->getMessage();
        }
    }

    protected function populateAccount(&$data)
    {
        $data['account'] = [
            'accountId' => $this->account->getAccountId(),
            'username' => $this->account->getUsername(),
            'email' => $this->account->getEmail(),
            'avatar' => asset('assets/img/my-account/male-user-avatar.png')
        ];

        $profile = $this->account->getProfile();
        if ($profile && strtolower($profile->getGender()) === 'female' ) {
             $data['account']['avatar'] = asset('assets/img/my-account/female-user-avatar.png');
        }
    }

    protected function populateProfile(&$data)
    {
        $resource = new ProfileResource($this->account->getProfile());

        $data['profile'] = $resource->toArray();
    }

    protected function populateSocialAccount(&$data)
    {
        $socialAccount = $this->account->getSocialAccount();

        if ($socialAccount) {
            $resource = new SocialAccountResource($this->account->getSocialAccount());
            $data['socialAccount'] = $resource->toArray();
        } else {
            $data['socialAccount'] = null;
        }
    }

    protected function populateMarketing(&$data)
    {
        $service = new MarketingSystemService();
        $marketingSystemAccount = $service->getMarketingSystemAccount($this->account->getAccountId());

        $data['marketing'] = [
            'affiliateId' => (int)$marketingSystemAccount->getHasOffersAffiliateId(),
            'offerId' => (int)$marketingSystemAccount->getHasOffersOfferId(),
            'transactionId' => $marketingSystemAccount->getHasOffersTransactionId() ?: null,
        ];
    }

    protected function populateMembership(&$data)
    {
        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = \EntityManager::getRepository(Subscription::class);
        $subscription = $subscriptionRepository->getTopPrioritySubscription($this->account);

        $data['membership'] = [
            'subscriptionId' => $subscription ? (int)$subscription->getSubscriptionId() : null,
            'name' => str_replace(' Membership', '', $subscription ? $subscription->getName() : 'Free'),
            'isMember' => $this->account->isMember(),
            'isFreemium' => $this->account->isFreemium(),
            'freemiumCredits' => (int)$this->account->getFreemiumCredits(),
            'credits' => (int)$this->account->getCredits(),
            'packagePrice' => $subscription ? $subscription->getPrice() : 0,
            'freeTrial' => $subscription && $subscription->isFreeTrial(),
            'freeTrialEndDate' => $subscription && $subscription->getFreeTrialEndDate() &&
                $subscription->getFreeTrialEndDate()->format('Y') !== '-0001' ?
                $subscription->getFreeTrialEndDate()->format('d/m/Y') : null,
            'startDate' => $subscription && $subscription->getStartDate() &&
                 $subscription->getStartDate()->format('Y') !== '-0001' ?
                 $subscription->getStartDate()->format('d/m/Y') : null,
            'endDate' => $subscription && $subscription->getEndDate() &&
                 $subscription->getEndDate()->format('Y') !== '-0001' ?
                 $subscription->getEndDate()->format('d/m/Y') : null,
            'renewalDate' => $subscription && $subscription->getRenewalDate() &&
                $subscription->getRenewalDate()->format('Y') !== '-0001' ?
                $subscription->getRenewalDate()->format('d/m/Y') : null,
            'activeUntil' => $subscription && $subscription->getActiveUntil() ?
                $subscription->getActiveUntil()->format('d/m/Y') : null,
            'expirationPeriodType' => $subscription ? $subscription->getExpirationPeriodType() : null,
            'recurrentTypeMessageFull' => $subscription ? $subscription->getPackage()->getRecurrentTypeMessageFull() : null,
            'expirationValue' => $subscription ? $subscription->getExpirationPeriodValue() : null,
            'packageAlias' => $subscription ? $subscription->getPackage()->getAlias() : null,
        ];
    }
}

