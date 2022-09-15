<?php

namespace App\Http\Controllers\Rest;

use App\Entity\Resource\PopupResource;
use App\Http\Controllers\RestController;
use App\Services\PopupService;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class PopupRestController extends RestController
{
    /**
     * @var PopupService
     */
    protected $popups;

    /**
     * PopupRestController constructor.
     *
     * @param EntityManager $em
     * @param PopupService  $popups
     */
    public function __construct(EntityManager $em, PopupService $popups)
    {
        parent::__construct($em);
        $this->popups = $popups;
    }

    protected function getRepository()
    {
        // TODO: Implement getRepository() method.
    }

    protected function getBaseIndexQuery(Request $request)
    {
        // TODO: Implement getBaseIndexQuery() method.
    }

    protected function getBaseIndexCountQuery(Request $request)
    {
        // TODO: Implement getBaseIndexCountQuery() method.
    }

    /**
     * @return PopupResource
     */
    protected function getResource()
    {
        return new PopupResource();
    }

    /**
     * @param string    $pageUrl
     * @param null      $accountId
     *
     * @return JsonResponse
     */
    public function display($pageUrl, $accountId = null)
    {
        $data = $this->popups->getPopupsByPage($pageUrl, $accountId);

        return $this->jsonResponse($data, null, $this->getResource())
            ->setPublic()
            ->setExpires(new \DateTime('+2 hours'));
    }
}
