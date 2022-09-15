<?php namespace App\Http\Controllers\Rest;

use App\Entities\ScholarshipTemplateContent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\ScholarshipTemplateContentController\UpdateActionRequest;
use App\Http\Requests\RestRequest;
use App\Services\ScholarshipManager\ContentManager;
use App\Transformers\ScholarshipTemplateContentTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\Fractal\TransformerAbstract;
use Pz\Doctrine\Rest\RestRepository;
use Pz\LaravelDoctrine\Rest\Action\ShowAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;

class ScholarshipTemplateContentController extends Controller
{
    const PDF_PREVIEW_TITLE = 'Preview';

    /**
     * @var TransformerAbstract
     */
    protected $transformer;

    /**
     * @var RestRepository
     */
    protected $repository;

    /**
     * ScholarshipTemplateContentController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(ScholarshipTemplateContent::class);
        $this->transformer = new ScholarshipTemplateContentTransformer();
    }

    /**
     * @param RestRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function show(RestRequest $request)
    {
        return (new ShowAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param UpdateActionRequest $request
     *
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function update(UpdateActionRequest $request)
    {
        return (new UpdateAction($this->repository, $this->transformer))->dispatch($request);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function previewPDF(Request $request)
    {
        $this->validate($request, ['html' => 'required']);

        /** @var ContentManager $contentManager */
        $contentManager = app(ContentManager::class);

        $domPdf = $contentManager->prepareHTMLtoPDF($request->get('html'), static::PDF_PREVIEW_TITLE);

        return Response::create($domPdf->output(), 200, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
