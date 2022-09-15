<?php namespace App\Http\Controllers\Api;

use App\Entity\Account;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Entity\Setting as EntitySetting;
use App\Services\ApplicationService;
use App\Services\EligibilityService;

use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;

use ScholarshipOwl\Data\Entity\Website\Setting;
use ScholarshipOwl\Data\Service\Scholarship\ScholarshipService;

/**
 * Apply Controller
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ApplyController extends BaseController
{
	const SESSION_SELECTED_SCHOLARSHIPS = "scholarships_selected";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EligibilityService
     */
    protected $es;

    /**
     * @var ScholarshipRepository
     */
    protected $repository;

    /**
     * @var \App\Services\ApplicationService
     */
    protected $service;

    /**
     * ApplyController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, ApplicationService $service, EligibilityService $es)
    {
        parent::__construct();

        $this->em = $em;
        $this->es = $es;
        $this->repository = $em->getRepository(Scholarship::class);
        $this->service = $service;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function indexAction(Request $request)
    {
		$model = $this->getOkModel("apply");

		try {
            /** @var Account $account */
            $account = \Auth::user();

            // Subscription Empty Visibility Settings
            $scholarshipVisibility = setting("scholarships.visibility");

			$scholarshipService = new ScholarshipService();
			$subscription = $this->getLoggedUserSubscription();
			$scholarships = [];

            $scholarshipIds = $this->repository->findEligibleNotAppliedScholarshipsIds($account);


            if ($request->get('reapply') == 1) {

                $automaticScholarshipArray = $this->repository->findAutomaticScholarships($account);

                if(!$automaticScholarshipArray->isEmpty()) {
                    $scholarships = $scholarshipService->getScholarshipsData(
                        $automaticScholarshipArray->first()->getScholarshipId()
                    );
                }

                return $model->setData(['scholarships' => $scholarships])->send();

            }

            // Don't get subscription for not subscribed and setting set to not show them
            if (!empty($subscription) || $scholarshipVisibility != Setting::VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_NONE) {
                $scholarships = $scholarshipService->getScholarshipsData($scholarshipIds);
            }

            // Filter scholarships for not subscribed user
            if (empty($subscription) && is_array($scholarships)) {
                if ($scholarshipVisibility == Setting::VALUE_SCHOLARSHIPS_VISIBILITY_SHOW_FREE) {
                    $scholarships = array_filter($scholarships, function ($scholarship) {
                        return $scholarship['is_free'] == '1';
                    });
                } elseif (is_numeric($scholarshipVisibility)) {
                    $scholarships = array_slice($scholarships, 0, $scholarshipVisibility);
                }
            }

            // Prepare for view
            if (is_array($scholarships)) {
                foreach ($scholarships as $scholarshipId => $scholarship) {
                    $scholarships[$scholarshipId]["expiration_date"] = date("m/d/Y", strtotime($scholarship["expiration_date"]));
                    $scholarships[$scholarshipId]["amount"] = number_format($scholarship["amount"]);
                    $scholarships[$scholarshipId]["is_recurrent"] = $scholarship["is_recurrent"];
                    $scholarships[$scholarshipId]["created_date"] = (isset($scholarship["created_date"]) && $scholarship["created_date"] > 0) ?
                        date("Y-m-d", strtotime($scholarship["created_date"])) : '2000-12-31';
                }
            }

			$model->setData(['scholarships' => $scholarships]);
		} catch(\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function postIndexAction(Request $request)
    {
        /** @var Account $account */
        $account = \Auth::user();
        $model = $this->getErrorModel(self::ERROR_CODE_APPLY_NO_CREDIT);

        $scholarshipsIds = $request->get('scholarships', []);

        $request->session()->put('payment_return', 'apply-selected');
        $request->session()->put(self::SESSION_SELECTED_SCHOLARSHIPS, $scholarshipsIds);

        if (empty($scholarshipsIds)) {
            return $this->getErrorModel(self::ERROR_CODE_APPLY_NOT_SELECTED)->send();
        }

		try {
            $applications = $this->service->applyFreeScholarships($account, $scholarshipsIds);

            if (!empty($applications) && $request->get('reapply')) {
                $redirect = setting(EntitySetting::SETTING_OFFER_WALL_AFTER_APPLY) ?? 'select';
                if ($request->get('noJs')) {
                   return redirect()->to($redirect);
                }
                $model = $this->getRedirectModel($redirect);
            }

        } catch (\Exception $e) {
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
            \Log::error($e);
		}

		return $model->send();
	}
}
