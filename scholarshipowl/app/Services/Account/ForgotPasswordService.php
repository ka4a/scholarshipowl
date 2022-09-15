<?php namespace App\Services\Account;

use App\Entity\Account;
use App\Entity\ForgotPassword;
use App\Services\Account\AccountService;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;

class ForgotPasswordService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var \App\Services\Account\AccountService
     */
    protected $accountService;

    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    protected $forgotPassword;

    /**
     * ForgotPasswordService constructor.
     *
     * @param EntityManager  $em
     * @param AccountService $accountService
     */
    public function __construct(EntityManager $em, AccountService $accountService)
    {
        $this->em = $em;
        $this->accountService = $accountService;
        $this->forgotPassword = $em->getRepository(ForgotPassword::class);
    }

    /**
     * @param Account $account
     *
     * @return null|ForgotPassword
     */
    public function findForgotPassword(Account $account)
    {
        return $this->forgotPassword->findOneBy(['account' => $account]);
    }

    /**
     * @param string $token
     * @return null|ForgotPassword
     */
    public function findByToken(string $token)
    {
        return $this->forgotPassword->findOneBy(['token' => $token]);
    }

    /**
     * @param Account $account
     * @param string  $token
     * @param string  $password
     *
     * @return bool
     */
    public function changePassword(Account $account, string $token, string $password): bool
    {
        $forgotPassword = $this->findForgotPassword($account);

        if (!$forgotPassword) {
            \Log::warning(
                sprintf(
                    'Wrong account [ %s ] provided for resetting password with token [ %s ]. No password reset token found.',
                    $account->getAccountId(),
                    $token
                )
            );
            return false;
        }

        if (strcmp($forgotPassword->getToken(), $token) !== 0) {
            \Log::warning(
                sprintf(
                    'Wrong token [ %s ] provided for resetting password with account [ %s ]. Token does not match.',
                    $token,
                    $account->getAccountId()
                )
            );
            return false;
        }

        $this->accountService->updatePassword($account, $password);
        $this->em->remove($forgotPassword);
        $this->em->flush();

        return true;
    }

    /**
     * Expire password reset token
     *
     * @param ForgotPassword $token
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function expire(ForgotPassword $token)
    {
        $token->setExpireDate(Carbon::now());
        $this->em->flush($token);
    }

    /**
     * @param Account $account
     * @param int     $expireDays
     *
     * @return ForgotPassword
     */
    public function updateToken(Account $account, int $expireDays = 7) : ForgotPassword
    {
        $token = $this->createToken($account);
        $expire = Carbon::now()->addDays($expireDays);

        if ($forgotPasswordToken = $this->findForgotPassword($account)) {
            $forgotPasswordToken->setToken($this->createToken($account));
            $forgotPasswordToken->setExpireDate($expire);
        } else {
            $this->em->persist($forgotPasswordToken = new ForgotPassword($account, $token, $expire));
        }

        $this->em->flush();

        return $forgotPasswordToken;
    }

    /**
     * @param string         $token
     * @param \DateTime|null $now
     *
     * @return bool
     */
    public function isTokenActive(string $token, \DateTime $now = null) : bool
    {
        return (bool) $this->em->createQueryBuilder()
            ->select('COUNT(f.account)')
            ->from(ForgotPassword::class, 'f')
            ->where('f.token = :token AND f.expireDate > :now')
            ->setParameter('token', $token)
            ->setParameter('now', $now ?: new \DateTime())
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Account $account
     *
     * @return string
     */
    protected function createToken(Account $account) : string
    {
        return md5(sprintf('%s_%s', $account->getEmail(), time()));
    }
}
