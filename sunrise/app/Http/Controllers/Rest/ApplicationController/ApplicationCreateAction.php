<?php namespace App\Http\Controllers\Rest\ApplicationController;

use App\Entities\Field;
use App\Entities\Scholarship;
use App\Services\ApplicationService;
use App\Http\Requests\RestRequest;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\LaravelDoctrine\Rest\Action\CreateAction;

class ApplicationCreateAction extends CreateAction
{
    /**
     * @param object|string $entity
     * @param array $data
     * @param string $scope
     * @return object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function hydrateEntity($entity, array $data, $scope = '')
    {
        $fields = $data['attributes'];

        if (isset($data['relationships']['state']['data']['id'])) {
            $fields[Field::STATE] = $data['relationships']['state']['data']['id'];
        }

        /** @var Scholarship $scholarship */
        $scholarship = $this->repository()
            ->getEntityManager()
            ->find(Scholarship::class, $data['relationships']['scholarship']['data']['id']);

        try {
            /** @var ApplicationService $as */
            $as = app(ApplicationService::class);
            $application = $as->apply($scholarship, $fields);
        } catch (ApplicationService\ApplicationServiceException $e) {
            throw RestException::createFromException($e);
        }

        return $application;
    }

    /**
     * @param RestRequest|\Pz\Doctrine\Rest\RestRequest $request
     * @param array $arguments
     * @return bool
     */
    public function authorize($request, $arguments = [])
    {
        if ($request->user()) {
            parent::authorize($request, $arguments);
        }
        return true;
    }
}
