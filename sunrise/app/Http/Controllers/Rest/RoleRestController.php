<?php namespace App\Http\Controllers\Rest;

use App\Entities\Role;
use App\Http\Controllers\RestController;
use App\Permission;
use App\Transformers\RoleTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Pz\Doctrine\Rest\RestRepository;

class RoleRestController extends RestController
{
    /**
     * UserRoleRestContoller constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->middleware('auth:api');
        $this->repository = new RestRepository($em, $em->getClassMetadata(Role::class));
        $this->transformer = new RoleTransformer();
    }
}
