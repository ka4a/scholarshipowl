<?php namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Entities\User;
use App\Repositories\ScholarshipRepository;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use App\Http\Requests\RestRequest;

class ScholarshipCollectionAction extends CollectionAction
{
    /**
     * @var array
     */
    protected $filterable = ['id'];

    /**
     * @param RestRequestContract|RestRequest $request
     * @param QueryBuilder $qb
     * @return CollectionAction
     */
    public function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        /** @var User $user */
        $user = $request->user();
        $alias = $qb->getRootAliases()[0];
        ScholarshipRepository::applyPublishedScholarships($qb, $alias);

        if ($user && !$user->isRoot()) {
            $qb->join('t.organisation', "to")
                ->join('to.roles', 'tro')
                ->join('tro.users', 'tu', 'WITH', 'tu.id = :user')
                ->setParameter('user', $user);
        }

        if ($domain = $request->input('domain')) {
            $qb->join('t.website', 'w')
                ->andWhere('w.domain = :domain')
                ->setParameter('domain', $domain);
        }

        return parent::applyFilter($request, $qb);
    }
}
