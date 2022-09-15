<?php namespace App\Http\Controllers\Rest;

use App\Entities\ApplicationFile;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rest\ApplicationController\ApplicationFileCreateAction;
use App\Http\Controllers\Rest\ApplicationController\ApplicationFileCreateRequest;
use App\Http\Controllers\RestController;
use App\Http\Requests\RestRequest;
use App\Transformers\ApplicationFileTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Response;
use Pz\LaravelDoctrine\Rest\Action\CreateAction;

class ApplicationFileController extends RestController
{
    /**
     * ApplicationFileController constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(ApplicationFile::class);
        $this->transformer = new ApplicationFileTransformer();
    }

    /**
     * @param ApplicationFileCreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function createFile(ApplicationFileCreateRequest $request)
    {
        return (new ApplicationFileCreateAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * Download application file.
     *
     * @param RestRequest $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function download(RestRequest $request)
    {
        /** @var ApplicationFile $file */
        $file = $this->repository()->findById($request->getId());

        return $file->download();
    }

    /**
     * @param RestRequest $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function file(RestRequest $request)
    {
        /** @var ApplicationFile $file */
        $file = $this->repository()->findById($request->getId());

        /** @var Gate $gate */
        $gate = app(Gate::class);
        $gate->authorize('restShow', $file);

        return Response::make(file_get_contents($file->getFile()), 200)
            ->header('Content-Type', $file->getMimeType())
            ->header('Content-Disposition', sprintf('inline; filename="%s"', $file->getName()));
    }
}
