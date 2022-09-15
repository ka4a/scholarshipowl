<?php namespace App\Http\Controllers\Rest;

use App\Entity\AccountFile;
use App\Entity\ApplicationFile;
use App\Entity\Contracts\ApplicationRequirementContract;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\ScholarshipResource;
use App\Entity\Scholarship;
use App\Http\Controllers\RestController;
use App\Services\ScholarshipService;
use Illuminate\Http\Request;

abstract class ApplicationRequirementAbstractController extends RestController
{
    /**
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        /** @var ApplicationRequirementContract|ApplicationFile $applicationRequirement */
        $this->authorize('destroy', $applicationRequirement = $this->findById($id));
        $account = $applicationRequirement->getAccount();

        if (method_exists($applicationRequirement, 'getAccountFile')) {
            $accountFile = $applicationRequirement->getAccountFile();
            // removal of account file triggers doctrine event postRemove which in its turn remove a file from GCS
            if ($accountFile) {
                $this->em->remove($accountFile);
            }
        }

        $this->em->remove($applicationRequirement);
        $this->em->flush();


        /** @var ScholarshipRepository $repository */
        $repository = $this->em->getRepository(Scholarship::class);
        $scholarship = $repository->findWithAccountApplications([$applicationRequirement->getScholarship()], $account);
        $scholarship = array_shift($scholarship);

        $scholarshipService = app(ScholarshipService::class);
        $favorites = $scholarshipService->getFavoritesScholarship($account);
        if(in_array($scholarship->getScholarshipId(), $favorites) && $scholarship instanceof Scholarship){
            $scholarship->setFavorite();
        }

        return $this->jsonResponse($scholarship, null, new ScholarshipResource($account));
    }
}
