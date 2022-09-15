<?php
# CrEaTeD bY FaI8T IlYa
# 2016

namespace App\Http\Controllers\ApplyMe;

use App\Entity\ApplyMe\ApplymeSettings;
use App\Entity\Repository\EntityRepository;
use App\Entity\Resource\ApplyMe\SettingsResource;
use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use ScholarshipOwl\Data\ResourceCollection;

class SettingsController extends Controller
{
    use JsonResponses;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $applymeSettingsRepo;

    /**
     * NotificationController constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->initRepos();
    }

    protected function initRepos()
    {
        $this->applymeSettingsRepo = $this->em->getRepository(ApplymeSettings::class);
    }

    /**
     * @return JsonResponse
     */
    public function index()
    {
		$settings = $this->applymeSettingsRepo->findAll();
		$resource = new ResourceCollection(new SettingsResource(), $settings);
		return $this->jsonSuccessResponse($resource->toArray());
    }
}
