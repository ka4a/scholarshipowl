<?php

namespace App\Http\Controllers\Rest;

use App\Entity\Resource\CoregsResource;
use Illuminate\Routing\Controller;
use App\Http\Traits\JsonResponses;
use App\Services\Marketing\CoregService;

class CoregsRestController extends Controller
{
    use JsonResponses;

    /**
     * @var CoregService
     */
    protected $cs;

    /**
     * CoregsRestController constructor.
     *
     * @param CoregService $cs
     */
    public function __construct(CoregService $cs)
    {
        $this->cs = $cs;
    }

    /**
     * @param $path
     * @param null $account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function coregs($path, $account = null)
    {
        $coregs = $this->cs->getCoregsByPath($path, $account);
        return $this->jsonSuccessResponse($coregs->toArray());
    }
}
