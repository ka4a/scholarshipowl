<?php namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\ApplicationFailedTries;
use App\Entity\Application;
use App\Entity\ApplicationStatus;
use App\Entity\Scholarship;
use Carbon\Carbon;
use Doctrine\ORM\Query;

class ApplicationRepository extends EntityRepository
{
    /**
     * @param Account|null $account
     * @param Scholarship|null $scholarship
     *
     * @return Query
     */
    public function getApplicationForSendingQuery(Account $account = null, Scholarship $scholarship = null)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->addSelect(['ac', 's', 'status'])
            ->join('a.account', 'ac')
            ->join('a.scholarship', 's')
            ->join('a.applicationStatus', 'status')
            ->where('a.applicationStatus = :applicationStatus')
            ->setParameter('applicationStatus', ApplicationStatus::PENDING);

        if ($account) {
            $queryBuilder
                ->andWhere('a.account = :account')
                ->setParameter('account', $account);
        }

        if ($scholarship) {
            $queryBuilder
                ->andWhere('a.scholarship = :scholarship')
                ->setParameter('scholarship', $scholarship);
        }

        return $queryBuilder->getQuery()->setHint(Query::HINT_REFRESH, true);
    }

    /**
     * @param Scholarship $scholarship
     *
     * @return Query
     */
    public function getAppliedAccountsQuery(Scholarship $scholarship)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->leftJoin('a.scholarship', 's')
            ->where('a.scholarship = :scholarship')
            ->setParameter('scholarship', $scholarship);

        return $queryBuilder->getQuery();
    }

    public function getSuccessfulApplicationsQuery()
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->select('count(a.account)')
            ->leftJoin('a.scholarship', 's')
            ->where('a.applicationStatus = :applicationStatus')
            ->andWhere('s.isAutomatic = false')
            ->setParameter('applicationStatus', ApplicationStatus::SUCCESS);

        return $queryBuilder;
    }

    /**
     * @param string $accountId
     * @param array $statuses
     *
     * @return array
     */
    public function getApplicationsByStatuses(string $accountId, array $statuses){
        $result = array();

        $query = $this->_em->createQuery(
            "SELECT IDENTITY(a.scholarship), IDENTITY(a.applicationStatus) FROM App\Entity\Application  as a WHERE a.account = :accountId AND a.applicationStatus in (:statuses)"
        )->setParameters([
           'accountId' => $accountId,
           'statuses' => $statuses
        ]);
        $resultSet = $query->getResult();

        foreach ($resultSet as $row) {
            $result[$row[1]] = $row[2];
        }

        return $result;
    }

    /**
     * @param int $period Number of hours for resend
     *
     * @return Query
     */
    public function getFailedApplicationQuery(int $resendIntervalHours = 4)
    {
        $queryBuilder = $this->createQueryBuilder('a')
            ->innerJoin(
                Scholarship::class, 's',
                Query\Expr\Join::WITH,
                'a.scholarship = s.scholarshipId'
            )
            ->leftJoin(
                ApplicationFailedTries::class, 'tries',
                Query\Expr\Join::WITH,
                'tries.accountId = a.account and tries.scholarshipId = a.scholarship'
            )
            ->where('a.applicationStatus = :applicationStatus')
            ->andWhere('(tries.tries > 0 or tries is null) and (tries.lastUpdate <= :triesDate or tries.lastUpdate is null)')
            ->setParameter('applicationStatus', ApplicationStatus::ERROR)
            ->setParameter('triesDate', Carbon::now()->subHour($resendIntervalHours));

        return $queryBuilder->getQuery();
    }

    /**
     * The total count of applications for a user
     *
     * @param Account $account
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function countApplications(Account $account)
    {
        $qb = $this->createQueryBuilder('a');

        return (int)$qb->select($qb->expr()->count('a.account'))
            ->andWhere('a.account = :account')
            ->setParameter('account', $account)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
