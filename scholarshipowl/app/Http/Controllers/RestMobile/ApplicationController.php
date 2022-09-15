<?php

namespace App\Http\Controllers\RestMobile;

use App\Entity\Application;
use App\Entity\ApplicationStatus;
use App\Entity\Repository\ScholarshipRepository;
use App\Entity\Resource\ApplicationResource;
use App\Entity\Scholarship;
use App\Http\Controllers\Rest\ApplicationRestController;
use App\Http\Traits\MobileResponseNormalize;
use App\Rest\Index\LimitAndStartQueryBuilder;
use Illuminate\Http\Request;
use Doctrine\ORM\Query;

class ApplicationController extends ApplicationRestController
{
    use MobileResponseNormalize;

    /**
     * @param $id
     * @param int $accountId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showApplication($id, int $accountId = null)
    {
        $this->authorize('show', $entity = $this->findById([
            'account' => $accountId ?: $this->getAuthenticatedAccount(),
            'scholarship' => $id,
        ]));
        $resource = $this->getResource();
        $resource->setWithApplicationData(true);

        $response = $this->jsonResponse($entity, [], $resource);

        $this->filterAndNormalize($response);

        return $response;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index(Request $request)
    {
        $account = $this->validateAccount($request);

        $this->authorize('index', $this->getRepository()->getClassName());

        $dataQuery = $this->getBaseIndexQueryBuilderChain($request)
            ->add(new LimitAndStartQueryBuilder($request))
            ->process($this->getBaseIndexQuery($request));

        $countQuery = $this->getBaseIndexQueryBuilderChain($request)
            ->process($this->getBaseIndexCountQuery($request));

        $applications = $dataQuery->getQuery()->setHint(Query::HINT_REFRESH, true)->getResult();


        if (!empty($applications)) {
            /** @var Application $application */
            foreach ($applications as $application) {
                $applicationStatusId = $application->getApplicationStatus()
                    ? $application->getApplicationStatus()->getId() : 0;

                if (in_array(
                        $applicationStatusId,
                        [
                            ApplicationStatus::SUCCESS,
                            ApplicationStatus::IN_PROGRESS,
                            ApplicationStatus::ERROR,
                            ApplicationStatus::PENDING,
                            ApplicationStatus::NEED_MORE_INFO,
                        ]
                    )
                ) {
                    $scholarship = $application->getScholarship();
                    /** @var ScholarshipRepository $repo */
                    $repo = \EntityManager::getRepository(Scholarship::class);
                    $status = $repo->getApplicationDerivedStatus($application);
                    $scholarship->setDerivedStatus($status);
                    $scholarship->setIsSent();
                }

                $scholarship->nl2br();
            }
        }

        $response = $this->jsonResponse(
            $applications,
            [
                'count' => (int) $countQuery->getQuery()->getSingleScalarResult(),
                'start' => (int) $dataQuery->getFirstResult(),
                'limit' => (int) $dataQuery->getMaxResults(),
            ],
            new ApplicationResource()
        );

        $this->filterAndNormalize($response);

        return $response;
    }
}
