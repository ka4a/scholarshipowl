<?php namespace App\Http\Controllers\Rest;

use App\Entities\Settings;
use App\Http\Controllers\RestController;
use App\Transformers\SettingsTransformer;
use Doctrine\ORM\EntityManager;
use Pz\Doctrine\Rest\RestRepository;

class SettingsController extends RestController
{
    /**
     * SettingsContentController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, Settings::class);
        $this->transformer = new SettingsTransformer();
    }
}
