<?php namespace App\Entity\Repository;

use App\Entity\FeatureAbTest;

class FeatureAbTestRepository extends EntityRepository
{
    /**
     * @return array|FeatureAbTest
     */
    public function findByActive()
    {
        return $this->createQueryBuilder('at')
            ->where('at.enabled = true')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $name
     *
     * @return null|object
     */
    public function findByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }
}
