<?php namespace App\Http\Controllers\Rest\ScholarshipWinnerController;

use Doctrine\ORM\QueryBuilder;
use Illuminate\Http\Request;
use Pz\Doctrine\Rest\Action\CollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\LaravelDoctrine\Rest\Action\IndexAction;

class ScholarshipWinnerIndexAction extends CollectionAction
{
    public function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        /**
         * Filter application by source.
         */
        if ($request instanceof Request) {
            if ($source = $request->get('source')) {
                $qb->join($qb->getRootAliases()[0].'.applicationWinner', 'aw')
                    ->join('aw.application', 'a')
                    ->andWhere('a.source = :source')
                    ->setParameter('source', $source);
            }
        }

        return parent::applyFilter($request, $qb);
    }
}
