<?php namespace App\Repositories;

use App\Entities\Application;
use App\Entities\ApplicationStatus;
use App\Entities\ApplicationWinner;
use App\Entities\Scholarship;
use Doctrine\Common\Collections\Criteria;
use Pz\Doctrine\Rest\RestRepository;

class ApplicationRepository extends RestRepository
{
    /**
     * @param Scholarship $scholarship
     * @return array|int[]
     */
    public function findAccepted(Scholarship $scholarship)
    {
        /** @var ApplicationWinnersRepository $applicationWinners */
        $applicationWinners =  $this->getEntityManager()
            ->getRepository(ApplicationWinner::class);

        $query = $this
            ->createQueryBuilder('a')
            ->select('a.id')
            ->where('a.scholarship = :scholarship AND a.status = :accepted')
            ->setParameter('accepted', ApplicationStatus::ACCEPTED)
            ->setParameter('scholarship', $scholarship);

        $disqualified = $applicationWinners->findDisqualifiedApplicationsIds($scholarship);
        if (!empty($disqualified)) {
            $query->andWhere('a.id NOT IN (:disqualified)')->setParameter('disqualified', $disqualified);
        }

        return array_map('current', $query->getQuery()->getArrayResult());
    }

    /**
     * @param Scholarship $scholarship
     * @return Application[]
     */
    public function queryUnreviewed(Scholarship $scholarship)
    {
        return $this->createQueryBuilder('a')
            ->addCriteria(
                Criteria::create()
                    ->andWhere(
                        Criteria::expr()->eq('a.scholarship', $scholarship)
                    )
                    ->andWhere(
                        Criteria::expr()->in('a.status', [ApplicationStatus::REVIEW, ApplicationStatus::RECEIVED])
                    )
            );
    }

    /**
     * @param Scholarship $scholarship
     * @return int
     */
    public function countUnreviewed(Scholarship $scholarship)
    {
        return $this->count([
            'scholarship' => $scholarship,
            'status' => [ApplicationStatus::REVIEW, ApplicationStatus::RECEIVED]
        ]);
    }
}
