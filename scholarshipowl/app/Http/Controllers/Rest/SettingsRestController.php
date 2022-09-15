<?php namespace App\Http\Controllers\Rest;

use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\Subscription;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use App\Services\SettingService;
use Doctrine\ORM\EntityManager;

class SettingsRestController extends Controller
{
    use JsonResponses;

    /**
     * @var SettingService
     */
    protected $settingService;

    /**
     * @var ScholarshipRepository
     */
    protected $scholarshipRepo;

    protected $privateSettings = [
        'memberships.active_text',
        'memberships.cancel_subscription_text',
        'memberships.cancelled_text',
        'memberships.freeTrial.cancel_subscription',
        'memberships.free_trial_active_text',
        'memberships.free_trial_cancelled_text',
        'memberships.free_trial_active_text'
    ];

    public function __construct(SettingService $ss, EntityManager $em)
    {
        $this->settingService = $ss;
        $this->scholarshipRepo = $em->getRepository(Scholarship::class);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicSettings()
    {
        $data = $this->prepareSettingsList();
        $result = $this->jsonSuccessResponse($data);

        return $result;
    }

    /***
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPrivateSettings()
    {
        $data = [];
        /**
         * @var Account $account
         */
        $account = \Auth::user();

        if ($account instanceof Account) {
            $activeSubscriptions = $account->getActiveSubscriptions();

            /**
             * @var Subscription $subscription
             */
            $subscription = $activeSubscriptions->first();
            foreach ($activeSubscriptions as $s) {
                if ($s->getPriority() < $subscription->getPriority()) {
                    $subscription = $s;
                }
            }

            $tags = null;
            if ($subscription) {
                $tags = [
                    '[[cancelUrl]]' => route('cancel-membership', $subscription->getSubscriptionId()),
                    '[[expirationDate]]' => !is_null($subscription->getRenewalDate()) ? $subscription->getRenewalDate()->format('F jS, Y') : '',
                    '[[subscription_renewal_date]]' => !is_null($subscription->getRenewalDate()) ? $subscription->getRenewalDate()->format('F jS, Y') : '',
                    '[[eligibility_count]]' => number_format((int)$this->scholarshipRepo->countEligibleScholarships($account), 0, '.', ','),
                    '[[eligibility_amount]]' => number_format((int)$this->scholarshipRepo->sumEligibleScholarships($account), 0, '.', ','),
                    '[[subscription_free_trial_end_date]]' => !is_null($subscription->getFreeTrialEndDate()) ? $subscription->getFreeTrialEndDate()->format('F jS, Y') : '',
                    '[[package_name]]' => !is_null($subscription) && !is_null($subscription->getPackage()) ? $subscription->getPackage()->getName() : '',
                ];
            }


            $data = $this->prepareSettingsList(false, $tags);
        }

        $result = $this->jsonSuccessResponse($data);
        return $result;
    }

    /**
     * @param $tags
     * @return array
     */
    protected function prepareSettingsList($isPublic = true, $tags = null): array
    {
        $data = [];

        $settingsList = $this->settingService->getAvailableSettingsInRest();

        //remove from setting list private
        if ($isPublic) {
            $settingsList = array_diff($settingsList, $this->privateSettings);
        }

        if ($fields = request()->get('fields', null)) {
            $fields = explode(',', $fields);
        }

        $fields = (is_null($fields) || empty($fields)) ? $settingsList : $fields;

        foreach ($fields as $field) {
            if (!empty($settingsList) && in_array($field, $settingsList)) {
                $value = $this->settingService->get($field);
                if (isset($tags)) {
                    $value = str_replace(array_keys($tags), array_values($tags), $value);
                }
                $data[$field] = $value;
            }
        }

        return $data;
    }

}
