<?php namespace App\Http\Controllers\Rest;

use App\Entity\AccountFile;
use App\Entity\AccountFileCategory;
use App\Entity\Repository\EntityRepository;
use App\Entity\Resource\AccountFileResource;
use App\Http\Controllers\RestController;
use Doctrine\Common\Collections\Criteria;
use Illuminate\Http\Request;

class AccountFileRestController extends RestController
{
    /**
     * @return EntityRepository
     */
    protected function getRepository()
    {
        return \EntityManager::getRepository(AccountFile::class);
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexQuery(Request $request)
    {
        $qb = $this->getRepository()->createQueryBuilder('af');

        if ($account = $this->getAuthenticatedAccount()) {
            $qb->andWhere('af.account = :authAccount');
            $qb->setParameter('authAccount', $account);
        }

        return $qb;
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function getBaseIndexCountQuery(Request $request)
    {
        $qb = $this->getBaseIndexQuery($request);
        return $qb->select($qb->expr()->count('af.id'));
    }

    /**
     * @return AccountFileResource
     */
    protected function getResource()
    {
        return new AccountFileResource();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $account = $this->validateAccount($request);
        $this->validate($request, [
            'account_file' => 'required|file',
        ]);

        $uploadedFile = $request->file('account_file', false);
        $accountFile = new AccountFile($uploadedFile, $account, $uploadedFile->getClientOriginalName());
        $this->em->persist($accountFile);
        $this->em->flush();

        return $this->jsonResponse($accountFile, null, $this->getResource());
    }

    /**
     * @param         $id
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $accountFile = $this->findById($id);

        if ($request->has('category_id') && ($category = AccountFileCategory::find($request->get('category_id')))) {
            $accountFile->setCategory($category);
        }

        if ($request->has('filename')) {
            $accountFile->setFileName($request->get('filename'));
        }

        $this->em->flush();

        return $this->jsonResponse($accountFile, null, $this->getResource());
    }
}
