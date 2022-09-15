<?php namespace ScholarshipOwl\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

interface ChainMemberInterface
{
    /**
     * Default priority for the member
     */
    const DEFAULT_PRIORITY = null;

    /**
     * @param QueryBuilder $qb
     *
     * @return QueryBuilder
     */
    public function handle(QueryBuilder $qb) : QueryBuilder;
}
