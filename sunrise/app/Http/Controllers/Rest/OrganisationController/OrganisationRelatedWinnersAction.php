<?php namespace App\Http\Controllers\Rest\OrganisationController;

use App\Entities\Organisation;
use App\Traits\HasAuthGate;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;

class OrganisationRelatedWinnersAction extends RelatedCollectionAction
{
    use HasAuthGate;

    /**
     * Add filter by relation entity.
     *
     * @param RestRequestContract $request
     * @param QueryBuilder        $qb
     *
     * @return $this
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        /** @var Organisation $organisation */
        $organisation = $this->base()->findById($request->getId());

        $this->gate()->authorize('relatedWinners', $organisation);

        $alias = $qb->getRootAliases()[0];
        $qb->join("$alias.application", 'a')
            ->join('a.scholarship', 's')
            ->join('s.template', 't')
            ->andWhere('t.organisation = :organisation')
            ->setParameter('organisation', $organisation)
            ->orderBy('a.createdAt', 'DESC');

        CollectionAction::applyFilter($request, $qb);
    }
}
