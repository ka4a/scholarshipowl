<?php

/**
 * Auto-generated action class
 */

declare(strict_types=1);

namespace App\Http\Controllers\Rest\IframeController;

use App\Entities\Iframe;
use App\Entities\ScholarshipTemplate;
use Illuminate\Contracts\Auth\Access\Gate;
use Pz\LaravelDoctrine\Rest\Action\CreateAction as BaseAction;

class CreateAction extends BaseAction
{
    /**
     * @param object|string $entity
     * @param array $data
     * @param string $scope
     * @return object
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function hydrateEntity($entity, array $data, $scope = '')
    {
        $template = $this->repository()
            ->getEntityManager()
            ->find(ScholarshipTemplate::class, $data['relationships']['template']['data']['id']);

        /** @var Gate $gate */
        $gate = app(Gate::class);
        $gate->authorize('restUpdate', $template);

        /** @var Iframe $entity */
        $entity = parent::hydrateEntity($entity, $data, $scope);
        $entity->setId($this->generateIframeCode());

        return $entity;
    }

    /**
     * @return string
     */
    protected function generateIframeCode()
    {
        return Iframe::CODE_PREFIX . strtoupper(str_random(13));
    }
}
