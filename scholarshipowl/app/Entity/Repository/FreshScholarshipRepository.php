<?php

namespace App\Entity\Repository;

use Doctrine\ORM\Query;

class FreshScholarshipRepository extends EntityRepository
{
    /**
     * Load last accounts fresh scholarships
     *
     * @param $accountsScholarships
     *
     * @return array
     */
    public function findByLastFreshScholarshipsListByIds($accountsScholarships)
    {
        $queryBuilder = $this->createQueryBuilder('f', 'f.accountId');
        $queryBuilder
            ->where(
                $queryBuilder->expr()->in(
                    'f.accountId',
                    implode(',', array_keys($accountsScholarships))
                )
            );

        return $queryBuilder->getQuery()->getResult();
    }
}
