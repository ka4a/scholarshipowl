<?php namespace App\Http\Controllers\Rest\UserController;


use App\Entities\User;
use App\Repositories\ScholarshipRepository;
use App\Transformers\ScholarshipTransformer;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\RestRepository;

/**
 * Provide list of viewable scholarship for the user.
 */
class RelatedScholarships extends RelatedCollectionAction
{
    public function __construct(RestRepository $repository, RestRepository $related)
    {
        parent::__construct($repository, null, $related, new ScholarshipTransformer());
    }

    /**
     * @return RestRepository|ScholarshipRepository
     */
    public function repository()
    {
        return parent::repository();
    }

    public function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        /** @var User $user */
        $user = $this->base()->findById($request->getId());
        $scholarships = $this->repository()->findByUser($user);

        $alias = $qb->getRootAliases()[0];
        $qb->andWhere("$alias.id IN (:scholarships)")
            ->setParameter('scholarships', $scholarships);

        return CollectionAction::applyFilter($request, $qb);
    }
}