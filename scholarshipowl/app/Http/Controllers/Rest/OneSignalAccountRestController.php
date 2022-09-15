<?php namespace App\Http\Controllers\Rest;

use App\Entity\OnesignalAccount;
use App\Entity\Repository\EntityRepository;
use App\Entity\Resource\OneSignalAccountResource;
use App\Http\Controllers\RestController;
use Illuminate\Http\Request;

class OneSignalAccountRestController extends RestController
{
    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(OnesignalAccount::class);
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('entity');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(1)');
    }

    /**
     * @return OneSignalAccountResource
     */
    public function getResource()
    {
        return new OneSignalAccountResource();
    }

    /**
     * Create one signal user.
     *
     * appId - required. OneSignal App identifier
     * userId - required. OneSinal Player identifier
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->authorize('store', OnesignalAccount::class);

        $this->validate($request, [
            'userId' => 'required|string|size:36',
            'app'    => 'required|string|max:8'
        ]);

        $account = $this->validateAccount($request);

        $criteria = ['account' => $account, 'userId' => $request->get('userId'), 'app' => $request->get('app')];
        if (null === ($oneSignalAccount = $this->getRepository()->findOneBy($criteria))) {
            $oneSignalAccount = new OnesignalAccount($account, $request->get('userId'), $request->get('app'));

            $this->em->persist($oneSignalAccount);
            $this->em->flush($oneSignalAccount);
        }

        return $this->jsonResponse($oneSignalAccount);
    }
}
