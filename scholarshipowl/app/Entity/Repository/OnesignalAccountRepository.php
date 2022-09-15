<?php namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\OnesignalAccount;

class OnesignalAccountRepository extends EntityRepository
{
    /**
     * @param Account $account
     * @param string  $app
     *
     * @return array
     */
    public static function findByAccount(Account $account, string $app)
    {
        $users = \EntityManager::createQueryBuilder()
            ->select(['osa.userId'])
            ->from(OnesignalAccount::class, 'osa')
            ->where('osa.account = :account AND osa.app = :app')
            ->setParameter('account', $account)
            ->setParameter('app', $app)
            ->getQuery()
            ->getScalarResult();

        return array_map('current', $users);
    }
}
