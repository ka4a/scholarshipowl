<?php namespace App\Http\Controllers\Api;

use App\Entity\Account;
use App\Entity\ApplicationEssay;
use App\Entity\Essay;
use App\Entity\Scholarship;
use App\Services\ApplicationService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class ScholarshipController extends BaseController
{

    /**
     * @var ApplicationService
     */
    private $applicationService;

    /**
     * ScholarshipController constructor.
     *
     * @param ApplicationService $applicationService
     */
    public function __construct(ApplicationService $applicationService)
    {
        parent::__construct();
        $this->applicationService = $applicationService;
    }

    /**
     * @param Account $account
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function applyAction(Account $account, Request $request)
    {
        $this->validate($request, [
            'scholarshipId'        => 'require|entity:Scholarship',
            'essay.*.essayId'      => 'require|entity:Essay',
            'essay.*.essayText'    => 'require',
        ]);

        /** @var Scholarship $scholarship */
        $scholarship = \EntityManager::findById(Scholarship::class, $request->get('scholarshipId'));

        try {

            $applicationEssays = array_map(
                function($essayInput) use ($account) {
                    /** @var Essay $essay */
                    $essay = \EntityManager::findById(Essay::class, $essayInput['essayId']);

                    return new ApplicationEssay($essay, $account, $essayInput['essayText']);
                },
                $request->get('essay')
            );

            $application = $this->applicationService->applyScholarship($account, $scholarship);


        } catch (\Exception $e) {
            //TODO: Build messages for each of application error type
            return $this->jsonErrorResponse($e->getMessage());
        }

        return $this->jsonSuccessResponse();
    }
}
