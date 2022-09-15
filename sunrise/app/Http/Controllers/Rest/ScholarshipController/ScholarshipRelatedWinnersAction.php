<?php namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Entities\Scholarship;
use Doctrine\ORM\QueryBuilder;
use League\Fractal\TransformerAbstract;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\RestRepository;

class ScholarshipRelatedWinnersAction extends RelatedCollectionAction
{
    /**
     * ScholarshipRelatedWinnersAction constructor.
     * @param RestRepository $repository
     * @param RestRepository $related
     * @param TransformerAbstract $transformer
     */
    public function __construct(RestRepository $repository, RestRepository $related, TransformerAbstract $transformer)
    {
        parent::__construct($repository, null, $related, $transformer);
    }

    /**
     * @param RestRequestContract $request
     * @param QueryBuilder $qb
     * @return $this
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        /** @var Scholarship $scholarship */
        $scholarship = $this->base()->findById($request->getId());

        $qb->join($qb->getRootAliases()[0].'.application', 'a')
            ->andWhere('a.scholarship = :scholarship')
            ->setParameter('scholarship', $scholarship);

        return CollectionAction::applyFilter($request, $qb);
    }
}
