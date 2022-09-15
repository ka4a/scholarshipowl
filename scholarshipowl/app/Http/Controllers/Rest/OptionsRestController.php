<?php namespace App\Http\Controllers\Rest;

use App\Http\Controllers\Controller;
use App\Http\Traits\JsonResponses;
use App\Services\OptionsManager;
use Illuminate\Http\Request;

class OptionsRestController extends Controller
{
    use JsonResponses;

    /**
     * @var OptionsManager
     */
    protected $om;

    /**
     * OptionsRestController constructor.
     *
     * @param OptionsManager $om
     */
    public function __construct(OptionsManager $om)
    {
        $this->om = $om;
    }

    /**
     * @param Request $request
     * @param null    $accountId
     *
     * @return mixed
     */
    public function index(Request $request, $accountId = null)
    {
        $this->validate($request, ['only' => 'array']);

        $options = $this->om->all($this->account($request, $accountId), $request->get('only'));

        return $this->jsonSuccessResponse($options);
    }
}
