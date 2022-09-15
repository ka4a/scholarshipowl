<?php namespace app\Http\Controllers\Rest;

use App\Entities\ScholarshipWebsite;
use App\Entities\ScholarshipWinner;
use App\Http\Controllers\Rest\ScholarshipWebsiteController\RelatedWinnersCreateAction;
use App\Http\Controllers\Rest\ScholarshipWebsiteController\RelatedWinnersCreateRequest;
use App\Http\Controllers\RestController;
use App\Repositories\ScholarshipWebsiteRepository;
use App\Transformers\ScholarshipWebsiteTransformer;
use App\Transformers\ScholarshipWinnerTransformer;

use Illuminate\Http\Response;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\Action\ItemAction;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestRepository;
use Doctrine\ORM\EntityManager;
use App\Http\Requests\RestRequest;
use Pz\Doctrine\Rest\RestResponseFactory;

class ScholarshipWebsiteController extends RestController
{
    /**
     * @var RestRepository
     */
    protected $winners;

    /**
     * ScholarshipTemplateController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(ScholarshipWebsite::class);
        $this->winners = RestRepository::create($em, ScholarshipWinner::class);
        $this->transformer = new ScholarshipWebsiteTransformer();
    }

    /**
     * @return ScholarshipWebsiteRepository
     */
    public function repository()
    {
        return parent::repository();
    }

    /**
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ItemAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * @deprecated Should be removed and moved to scholarship.
     *
     * @param RelatedWinnersCreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWinnersCreate(RelatedWinnersCreateRequest $request)
    {
        return (
            new RelatedWinnersCreateAction(
                $this->repository(), 'winners', 'website', $this->winners, new ScholarshipWinnerTransformer()
            )
        )->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @param $domain
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws RestException
     */
    public function showByDomain(RestRequest $request, $domain)
    {
        $website = $this->repository()->findByDomain($domain);

        if (is_null($website)) {
            $message = 'Website not found!';
            throw RestException::create(Response::HTTP_NOT_FOUND, 'Entity not found.')
                ->error('entity-not-found', ['type' => 'scholarship', 'domain' => $domain], $message);
        }

        $resource = new Item($website, $this->transformer(), $website->getResourceKey());

        return (new RestResponseFactory())->resource($request, $resource);
    }
}
