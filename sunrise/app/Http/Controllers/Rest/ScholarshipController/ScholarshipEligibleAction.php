<?php namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Services\ApplicationService;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;

class ScholarshipEligibleAction extends ScholarshipCollectionAction
{
    /**
     * @param RestRequestContract $request
     * @param QueryBuilder $qb
     * @return $this|CollectionAction
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        parent::applyFilter($request, $qb);

        $alias = $qb->getRootAliases()[0];

        /** @var ApplicationService $applicationService */
        $applicationService = app(ApplicationService::class);
        $eligibleScholarshipIds = $applicationService->eligible($request->getData());

        $qb->andWhere(
            $qb->expr()->in("$alias.id", empty($eligibleScholarshipIds) ? ['none'] : $eligibleScholarshipIds)
        );

        return $this;
    }
}
