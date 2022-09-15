<?php
namespace App\Services;

use App\Entity\Account;
use App\Entity\AccountFile;
use App\Entity\Repository\EntityRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FileService
{
    /**
     * @var EntityRepository
     */
    protected $repository;

    public function __construct()
    {
        $this->repository =  \EntityManager::getRepository(AccountFile::class);
    }

    /**
     * @param $path
     * @param $account
     *
     * @return AccountFile
     */
    public function getAccountFileByPath($path, $account)
    {
        /** @var AccountFile $accountFile */
        if (null === ($accountFile = $this->repository->findOneBy(['path' => '/' . ltrim($path, '/'),  'account' => $account->getAccountId()]))) {
            throw new NotFoundHttpException();
        }

        return $accountFile;
    }

    /**
     * @param $fileId
     * @param $account
     *
     * @return AccountFile
     */
    public function getAccountFileById($fileId, $account)
    {
        /** @var AccountFile $accountFile */
        if (null === ($accountFile = $this->repository->findOneBy(['id' => $fileId, 'account' => $account->getAccountId()]))) {
            throw new NotFoundHttpException();
        }

        return $accountFile;
    }
}