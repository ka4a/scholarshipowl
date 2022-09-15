<?php namespace App\Http\Controllers\Rest;

use App\Entities\Field;
use App\Http\Controllers\RestController;
use App\Transformers\FieldTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\RestRepository;

class FieldsController extends RestController
{
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, Field::class);
        $this->transformer = new FieldTransformer();
    }
}
