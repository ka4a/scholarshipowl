<?php namespace App\Http\Controllers\Rest\OrganisationController;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;

class RelatedScholarshipsCollection extends RelatedCollectionAction
{
    /**
     * @param RestRequestContract $request
     * @param QueryBuilder $qb
     * @return $this|RelatedCollectionAction
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function applyPagination(RestRequestContract $request, QueryBuilder $qb)
    {
        $orderBy = $request->getOrderBy();

        if (isset($orderBy['published'])) {
            $qb->leftJoin(
                sprintf('%s.published', $qb->getRootAliases()[0]),
                'published',
                'WITH',
                'published.expiredAt IS NULL'
            )
                ->addSelect('CASE WHEN published.id IS NOT NULL AND published.expiredAt IS NULL THEN 1 ELSE 0 END AS HIDDEN _isExpired')
                ->addOrderBy('_isExpired', 'DESC')
                ->groupBy(sprintf('%s.id', $qb->getRootAliases()[0]));
            unset($orderBy['published']);
        }

        $qb->addCriteria(
            new Criteria(null,
                $orderBy,
                $request->getStart(),
                $request->getLimit()
            )
        );

        return $this;
    }
}