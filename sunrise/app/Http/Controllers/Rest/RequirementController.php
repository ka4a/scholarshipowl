<?php namespace App\Http\Controllers\Rest;

use App\Entities\Requirement;
use App\Http\Controllers\RestController;
use App\Transformers\RequirementTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\RestRepository;

class RequirementController extends RestController
{
    /**
     * RequirementController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, Requirement::class);
        $this->transformer = new RequirementTransformer();
    }
}
