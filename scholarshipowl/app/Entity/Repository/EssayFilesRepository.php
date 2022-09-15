<?php namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\Essay;
use App\Entity\EssayFiles;

class EssayFilesRepository extends EntityRepository
{
    /**
     * @param Essay   $essay
     * @param Account $account
     *
     * @return array|EssayFiles[]
     */
    public function findByEssayAndAccount(Essay $essay, Account $account)
    {
        return $this->createQueryBuilder('ef')
            ->join('ef.file', 'af')
            ->where('af.account = :account AND ef.essay = :essay')
            ->setParameter('account', $account)
            ->setParameter('essay', $essay)
            ->getQuery()->getResult();
    }
}
