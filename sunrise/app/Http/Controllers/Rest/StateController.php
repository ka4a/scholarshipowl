<?php namespace App\Http\Controllers\Rest;

use App\Entities\State;
use App\Http\Controllers\RestController;
use App\Transformers\StateTransformer;
use Doctrine\ORM\EntityManager;

class StateController extends RestController
{
    /**
     * StateController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(State::class);
        $this->transformer = new StateTransformer();
    }
}
