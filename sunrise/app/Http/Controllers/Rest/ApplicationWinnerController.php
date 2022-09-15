<?php namespace App\Http\Controllers\Rest;

use App\Entities\ApplicationWinner;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\ApplicationWinnerController\ApplicationWinnerUpdateRequest;
use App\Http\Controllers\Rest\ApplicationWinnerController\ApplicationWinnerIndexAction;
use App\Services\GoogleVision;
use App\Transformers\ApplicationWinnerTransformer;

use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestResponse;
use Pz\LaravelDoctrine\Rest\Action\ShowAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;
use App\Http\Requests\RestRequest;
use Doctrine\ORM\EntityManager;

class ApplicationWinnerController extends Controller
{
    /**
     * @var ApplicationWinnerTransformer
     */
    protected $transformer;

    /**
     * @var RestRepository
     */
    protected $repository;

    /**
     * ApplicationWinnerController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = RestRepository::create($em, ApplicationWinner::class);
        $this->transformer = new ApplicationWinnerTransformer();
    }

    /**
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function index(RestRequest $request)
    {
        return (new ApplicationWinnerIndexAction($this->repository, $this->transformer))
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ShowAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param ApplicationWinnerUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function update(ApplicationWinnerUpdateRequest $request)
    {
        return (new UpdateAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return mixed
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function face(RestRequest $request)
    {
        /** @var ApplicationWinner $winner */
        $winner = $this->repository->findById($request->getId());

        try {
            /** @var GoogleVision $googleVision */
            $googleVision = app(GoogleVision::class);
            $image = $googleVision->findWinnerFace($winner)->encode();
        } catch (\Exception $e) {
            \Log::error($e);
            return RestResponse::create(null, RestResponse::HTTP_NOT_FOUND);
        }

        return RestResponse::create([
            'mime' => $image->mime(),
            'name' => $image->basename,
            'base64' => base64_encode($image->getEncoded()),
        ]);
    }
}
