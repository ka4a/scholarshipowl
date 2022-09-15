<?php

namespace App\Http\Controllers\Rest;

use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Scholarship;
use App\Rest\Traits\RestAuthorization;
use App\Services\EligibilityCacheService;
use App\Services\EligibilityService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Traits\JsonResponses;

class EligibilityCacheController extends Controller
{
    use JsonResponses;
    use RestAuthorization;

    /**
     * @var EligibilityCacheService
     */
    protected $elbCacheService;


    public function __construct()
    {
        $this->elbCacheService = app()->get(EligibilityCacheService::class);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateShownScholarships(Request $request)
    {
        try {
            $request->validate([
                'last_shown_scholarship_ids' => 'required|array',
            ]);

            $scholarshipIds = $request->get('last_shown_scholarship_ids');
            $account = $this->getAuthenticatedAccount();

            $this->elbCacheService->updateAccountLastShownScholarships($account->getAccountId(), $scholarshipIds);
            return $this->jsonSuccessResponse([]);
        } catch (\Exception $e) {
            \Log::error($e);
            return $this->jsonErrorResponse('Can\'t update eligibility cache. Error: '. $e->getMessage());
        };
    }

    /**
     * @param Request $request
     * @param null $fields
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEligibilityCache(Request $request, $fields = null)
    {
        $result = $this->jsonErrorResponse('Can\'t fetch eligibilities for user ');
        $account = $this->getAuthenticatedAccount();

        if (!is_null($account)) {
            try {
                /**
                 * @var EligibilityCache $eligibilities
                 */
                $eligibilities = $this->elbCacheService->getAccountEligibilityCache($account->getAccountId());
                $dataArray = $eligibilities->toArray();

                $fields = str_word_count(request()->get('fields'), 1);
                if (!empty($fields)) {
                    $dataArray = array_intersect_key($dataArray, array_flip($fields));
                }

                return $this->jsonSuccessResponse($dataArray);
            } catch (\Exception $e) {
                \Log::error($e);
                return $this->jsonErrorResponse('Can\'t fetch eligibility cache. Error: ' . $e->getMessage());
            }
        }

    }

    public function calculateInitialEligibility(Request $request)
    {
        $request->validate([
            'age' => 'required|integer',
            'school_level' => 'required|integer',
            'degree' => 'required|integer',
            'gender' => 'required|string|in:male,female,other',
        ]);

        $params = $request->all();

        /** @var EligibilityService $elbService */
        $elbService = app(EligibilityService::class);
        $scholarshipIds = $elbService->getBasicEligibilityScholarshipIds(
            $params['gender'],
            $params['school_level'],
            $params['degree'],
            $params['age']
        );

        /** @var ScholarshipRepository $scholarshipRepo */
        $scholarshipRepo = \EntityManager::getRepository(Scholarship::class);
        $response = [
            'count' => count($scholarshipIds),
            'amount' => (int)$scholarshipRepo->sumEligibleScholarships($scholarshipIds)
        ];

        return $this->jsonSuccessResponse($response);
    }
}
