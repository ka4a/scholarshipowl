<?php

namespace App\Services\Account;

use App\Entity\Account;
use App\Entity\AccountLoginToken;
use App\Entity\Repository\EntityRepository;
use Carbon\Carbon;
use Doctrine\DBAL\Driver\PDOSqlsrv\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class AccountLoginTokenService
{
    const MAX_TOKEN_COUNT = 5;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var int
     */
    protected $expireInDays;

    /**
     * AccountLoginTokenService constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->expireInDays = setting("register.login_token_validity");
    }

    /**
     * @param int $daysCnt
     */
    public function setExpireInDays(int $daysCnt): self
    {
        $this->expireInDays = $daysCnt;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpireInDays(): int
    {
        return $this->expireInDays;
    }

    /**
     * @return EntityRepository
     */
    protected function repository(): EntityRepository
    {
        return $this->em->getRepository(AccountLoginToken::class);
    }

    /**
     * @param Account[] $accounts
     * @return AccountLoginToken[]|array
     */
    public function findByAccounts(array $accounts): array
    {
        return $this->repository()->createQueryBuilder('t')
            ->where('t.account IN (:account)')
            ->setParameter('account', $accounts, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
            ->orderBy('t.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $offsetId
     * @param int $limit
     * @return array
     */
    public function findOutdated(int $offsetId = 0, int $limit = 1000): array
    {
        return $this->repository()->createQueryBuilder('t')
            ->where("t.id > :id")
            ->andWhere("t.createdAt < :expirationDate")
            ->setParameter('id', $offsetId)
            ->setParameter('expirationDate', Carbon::now()->subDays($this->getExpireInDays()))
            ->orderBy('t.id', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Account $account
     * @param bool $generateIfNotFound
     * @return AccountLoginToken
     */
    public function getLatestToken(Account $account, $generateIfNotFound = true): AccountLoginToken
    {
        $tokens = $this->findByAccounts([$account]);

        if ($tokens) {
            return $tokens[0];
        }

        return $this->generateTokens([$account], false)[$account->getAccountId()];
    }

    /**
     * @param $token
     * @return AccountLoginToken|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function verifyLoginToken($token, $ttlDays = null): ?AccountLoginToken
    {
        $expirationDate = Carbon::now()->subDays($ttlDays ?? $this->getExpireInDays());

        return $this->repository()
            ->createQueryBuilder('t')
            ->where('t.token = :token AND t.isUsed = false AND t.createdAt > :expirationDate')
            ->setParameter('token', $token)
            ->setParameter('expirationDate', $expirationDate)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param AccountLoginToken $accountLoginToken
     *
     * @return bool
     */
    public function expireLoginToken(AccountLoginToken $accountLoginToken): bool
    {
        $accountLoginToken->setIsUsed(true);

        $this->em->flush($accountLoginToken);

        return true;
    }

    /**
     * Generate new tokens, remove old once if total count of tokens for an account > self::MAX_TOKEN_COUNT.
     *
     * @param Account[] $accounts
     * @param bool $returnOnlyToken TRUE to returns tokens as plain text instead of entities
     * @return array
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function generateTokens(array $accounts, $returnOnlyToken = true): array
    {
        $tokensByAccount = [];
        /** @var AccountLoginToken $t */
        foreach ($this->findByAccounts($accounts) as $t) {
            $tokensByAccount[$t->getAccount()->getAccountId()][] = $t;
        }

        $generatedTokens = [];
        foreach ($accounts as $account) {
            $existingTokens = $tokensByAccount[$account->getAccountId()] ?? [];

            if (count($existingTokens) >= self::MAX_TOKEN_COUNT) {
                $tokensToDelete = array_slice($existingTokens, self::MAX_TOKEN_COUNT - 1);
                $this->deleteTokens($tokensToDelete);
            }

            $token = new AccountLoginToken($account);
            $this->em->persist($token);
            $generatedTokens[$account->getAccountId()] = $returnOnlyToken ? $token->getToken() : $token;
        }

        $this->em->flush();

        return $generatedTokens;
    }

    /**
     * @param array $tokens
     * @param bool $flush
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteTokens(array $tokens, $flush = false): void
    {
        /** @var AccountLoginToken $t */
        foreach ($tokens as $t) {
            $this->em->remove($t);
        }

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * @return int Number of deleted records
     */
    public function deleteOutdated(): int
    {
        return \DB::table('account_login_token')
            ->whereDate('created_at', '<',  Carbon::now()->subDays($this->getExpireInDays()))
            ->delete();
    }

    /**
     * @param AccountLoginToken[] $tokens
     * @return array
     */
    public static function pluckTokenIds(array $tokens): array
    {
        $tokenIds = [];

        /** @var AccountLoginToken $t */
        foreach ($tokens as $t) {
            $tokenIds[] = $t->getId();
        }

        return $tokenIds;
    }
}
