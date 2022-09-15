<?php namespace App\Http\Controllers\Api;

use App\Entity\Account;
use App\Entity\Scholarship;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\AccountResource;
use App\Entity\Resource\ScholarshipResource;
use App\Http\Traits\JsonResponses;

use ScholarshipOwl\Data\ResourceCollection;

class AccountController extends BaseController
{
    use JsonResponses;

    /**
     * Provides account base info.
     *
     * @param Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountAction(Account $account)
    {
        return $this->jsonSuccessResponse((new AccountResource($account))->toArray());
    }

    /**
     * @param Account $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function scholarshipsAction(Account $account)
    {
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = \EntityManager::getRepository(Scholarship::class);
        $scholarshipCollection = new ResourceCollection(
            new ScholarshipResource(),
            $scholarshipRepository->findEligibleScholarships($account)
        );

        return $this->jsonSuccessResponse([
            'scholarships' => $scholarshipCollection->toArray(),
        ]);
    }
}
