<?php namespace App\Repositories;

use App\Entities\Scholarship;
use Pz\Doctrine\Rest\RestRepository;

class ApplicationWinnersRepository extends RestRepository
{
    /**
     * @param Scholarship $scholarship
     * @return array|int[]
     */
    public function findDisqualifiedApplicationsIds(Scholarship $scholarship)
    {
        $this->getEntityManager()->getFilters()->disable('soft-deleteable');
        $disqualified = array_map('current',
            $this
                ->createQueryBuilder('w')
                ->join('w.application', 'a')
                ->select('IDENTITY(w.application)')
                ->where('a.scholarship = :scholarship AND w.disqualifiedAt IS NOT NULL')
                ->setParameter('scholarship', $scholarship)
                ->getQuery()
                ->getArrayResult()
        );
        $this->getEntityManager()->getFilters()->enable('soft-deleteable');

        return $disqualified;
    }

    /**
     * @param Scholarship $scholarship
     * @return int
     */
    public function countWinners(Scholarship $scholarship)
    {
        return (int) $this->createQueryBuilder('w')
            ->select('count(w.id)')
            ->join('w.application', 'a')
            ->where('a.scholarship = :scholarship AND w.disqualifiedAt IS NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
