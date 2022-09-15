<?php

/**
 * Auto-generated action class
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest\UserTokenController;

use App\Entities\UserToken;
use App\Http\Requests\RestRequest;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\CreateAction as BaseAction;

class CreateAction extends BaseAction
{
    /**
     * @param \Pz\Doctrine\Rest\Contracts\RestRequestContract|RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    protected function handle($request)
    {
        $headers = [];
        $class = $this->repository()->getClassName();
        $em = $this->repository->getEntityManager();


        $this->authorize($request, $class);

        /** @var UserToken $entity */
        $entity = $this->hydrateEntity($class, $request->getData());
        $entity->setUser($request->user());

        do {
            // base64_encode(random_bytes(64));
            $entity->setToken(substr(bin2hex(random_bytes(64)), 0 ,40));
        } while (
            $em->getRepository(UserToken::class)
                ->findOneBy(['token' => $entity->getToken()])
        );

        $this->validateEntity($entity);
        $this->repository()->getEntityManager()->persist($entity);
        $this->repository()->getEntityManager()->flush();

        if ($entity instanceof JsonApiResource) {
            $headers['Location'] = $this->repository()->linkJsonApiResource($request, $entity);
        }

        $resource = new Item($entity, $this->transformer(), $entity->getResourceKey());
        return $this->response()->resource($request, $resource, RestResponse::HTTP_CREATED, $headers);
    }
}
