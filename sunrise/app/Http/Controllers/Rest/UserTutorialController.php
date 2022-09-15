<?php namespace App\Http\Controllers\Rest;

use App\Entities\UserTutorial;
use App\Http\Controllers\RestController;
use App\Transformers\UserTutorialTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\RestRepository;

class UserTutorialController extends RestController
{
    /**
     * UserTutorialController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, UserTutorial::class);
        $this->transformer = new UserTutorialTransformer();
    }
}
