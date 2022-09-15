<?php namespace App\Http\Controllers\Admin\Api;

use App\Entity\Admin\AdminActivityLog;
use App\Entity\Resource\Admin\ActivityLogResource;
use App\Http\Controllers\RestController;
use App\Rest\Index\OrderByQueryBuilder;
use Illuminate\Http\Request;

class ActivityLogRestController extends RestController
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return \EntityManager::getRepository(AdminActivityLog::class);
    }

    /**
     * @return ActivityLogResource
     */
    public function getResource()
    {
        return new ActivityLogResource();
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexQuery(Request $request)
    {
        return $this->getRepository()->createQueryBuilder('al');
    }

    /**
     * @param Request $request
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getBaseIndexCountQuery(Request $request)
    {
        return $this->getBaseIndexQuery($request)->select('COUNT(al.id)');
    }
}
