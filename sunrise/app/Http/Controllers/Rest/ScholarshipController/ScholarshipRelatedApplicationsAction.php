<?php
/**
 * Created by PhpStorm.
 * User: r3volut1oner
 * Date: 28/02/19
 * Time: 03:50
 */

namespace App\Http\Controllers\Rest\ScholarshipController;

use App\Entities\Scholarship;
use App\Http\Controllers\Rest\ApplicationController\ApplicationDataFilterParser;
use Doctrine\ORM\QueryBuilder;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;

class ScholarshipRelatedApplicationsAction extends RelatedCollectionAction
{
    /**
     * @var string
     */
    protected $filterProperty = 'email';

    /**
     * @var array
     */
    protected $filterable = [
        'id',
        'source',
        'name',
        'email',
        'status'
    ];

    /**
     * @param RestRequestContract $request
     * @param QueryBuilder $qb
     * @return QueryBuilder|RelatedCollectionAction
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    protected function applyFilter(RestRequestContract $request, QueryBuilder $qb)
    {
        parent::applyFilter($request, $qb);

        /** @var Scholarship $scholarship */
        $scholarship = $this->base()->findById($request->getId());

        $applicationDataFilter = new ApplicationDataFilterParser($scholarship);
        return $applicationDataFilter->applyFilter($qb, $request->getFilter());
    }
}