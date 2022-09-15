<?php namespace App\Entity\Repository;

use App\Entity\Account;
use App\Entity\Domain;
use App\Entity\Exception\EntityNotFound;

class AccountRepository extends EntityRepository
{
    /**
     * @param string $username
     *
     * @return Account
     */
    public function findByUsername(string $username)
    {
        return $this->findOneBy(['username' => $username]);
    }

    /**
     * @param string $braintreeId
     *
     * @return Account
     */
    public function findByBraintreeId(string $braintreeId): Account
    {
        if (null === ($account = $this->findOneBy(['braintree_id' => $braintreeId]))) {
            throw new EntityNotFound(Account::class);
        }

        return $account;
    }

    /**
     * @param $limit
     * @return array
     */
    public function findLatestAccounts($limit = 5)
    {
        return $this->createQueryBuilder('a')
                ->orderBy('a.accountId', 'DESC')
                ->setMaxResults($limit)
                ->getQuery()
                ->getResult();
    }

    /**
     * @param string     $email
     * @param int|Domain $domain
     *
     * @return null|Account
     */
    public function findByEmail(string $email, $domain = null)
    {
        return $this->findOneBy([
            'email'  => $email,
            'domain' => $domain ?: \Domain::get(),
        ]);
    }

    /**
     * @param string $referralCode
     *
     * @return null|Account
     */
    public function findByReferralCode(string $referralCode)
    {
        return $this->findOneBy(['referralCode' => $referralCode]);
    }
}
