<?php namespace App\Http\Action\Related;

use \Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction as Base;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Traits\HandlesAuthorization;

class RelatedCollectionAction extends Base
{
    use HandlesAuthorization;

    /**
     * @param RestRequestContract $request
     * @return RestResponse
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function handle($request)
    {
        $this->authorize($request, $this->repository()->getClassName());

        $qb = $this->repository()->sourceQueryBuilder($request);
        $this->applyPagination($request, $qb);
        $this->applyFilter($request, $qb);

        return $this->response()->resource($request, $this->prepareCollection($request, $qb));
    }

    /**
     * @return string
     */
    protected function restAbility()
    {
        return 'restIndex';
    }
}