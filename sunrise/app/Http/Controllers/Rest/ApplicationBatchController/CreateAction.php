<?php namespace App\Http\Controllers\Rest\ApplicationBatchController;

use App\Entities\ApplicationBatch;
use App\Entities\Scholarship;
use App\Http\Controllers\Rest\ScholarshipController\ScholarshipCollectionAction;
use App\Jobs\BatchScholarshipApply;
use App\Repositories\ScholarshipRepository;
use App\Services\ApplicationService;
use Illuminate\Http\Response;
use Pz\Doctrine\Rest\Resource\Item;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestRequest;
use Pz\Doctrine\Rest\RestResponse;

class CreateAction extends ScholarshipCollectionAction
{
    /**
     * @return string
     */
    protected function restAbility()
    {
        return 'restCreate';
    }

    /**
     * @param RestRequest|CreateRequest $request
     *
     * @return RestResponse
     * @throws \Exception
     */
    protected function handle($request)
    {
        $em = $this->repository()->getEntityManager();
        $attributes = $request->getData()['attributes'];
        $this->authorize($request, $this->repository()->getClassName());

        /** @var ApplicationService $service */
        $service = app(ApplicationService::class);
        $fields = $service->verifyEligibilityData($attributes);

        /** @var ScholarshipRepository $scholarships */
        $scholarships = RestRepository::create($em, Scholarship::class);

        $qb = $scholarships->sourceQueryBuilder($request);
        $this->applyPagination($request, $qb);
        $this->applyFilter($request, $qb);

        $eligible = $service->eligible($fields, $qb->getQuery(), true);

        $batch = new ApplicationBatch();
        $batch->setData($fields);
        $batch->setEligible(count($eligible));
        if (isset($attributes['source'])) {
            $batch->setSource($attributes['source']);
        }

        $em->persist($batch);
        $em->flush($batch);

        BatchScholarshipApply::dispatch($batch, $eligible);

        return $this->response()
            ->resource($request, new Item($batch, $this->transformer()), Response::HTTP_CREATED);
    }
}
