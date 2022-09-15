<?php namespace App\Rest\Index;

use Doctrine\ORM\QueryBuilder;

class LimitAndStartQueryBuilder extends AbstractChainMember
{
    /**
     * Default builder priority
     */
    const DEFAULT_PRIORITY = 1000;

    /**
     * HTTP Param for start from
     */
    const PARAM_START = 'start';

    /**
     * HTTP Param for handle limiting results
     */
    const PARAM_LIMIT = 'limit';

    /**
     * Default results limit
     */
    const DEFAULT_LIMIT = 1000;

    /**
     * @inheritdoc
     */
    public function handle(QueryBuilder $qb) : QueryBuilder
    {
        $qb->setMaxResults($this->request->get(static::PARAM_LIMIT, static::DEFAULT_LIMIT));
        $qb->setFirstResult($this->request->get(static::PARAM_START, 0));

        return $qb;
    }
}
