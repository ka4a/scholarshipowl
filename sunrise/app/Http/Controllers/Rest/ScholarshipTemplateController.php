<?php namespace App\Http\Controllers\Rest;

use App\Doctrine\Types\RecurrenceConfigType\ConfigFactory;
use App\Entities\Iframe;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipTemplateContent;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipTemplateRequirement;
use App\Entities\ScholarshipTemplateSubscription;
use App\Entities\ScholarshipWebsite;
use App\Http\Controllers\Rest\ScholarshipTemplateController\RelatedFieldsUpdateAction;
use App\Http\Controllers\Rest\ScholarshipTemplateController\RelatedFieldsUpdateRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateController\RelatedRequirementsUpdateAction;
use App\Http\Controllers\Rest\ScholarshipTemplateController\RelatedRequirementsUpdateRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateController\RelatedSubscriptionRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateController\ScholarshipTemplateCreateRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateController\ScholarshipTemplateRelatedWebsiteCreateRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateController\ScholarshipTemplateRelatedWebsiteUpdateAction;
use App\Http\Controllers\Rest\ScholarshipTemplateController\ScholarshipTemplateRelatedWebsiteUpdateRequest;
use App\Http\Controllers\Rest\ScholarshipTemplateController\ScholarshipTemplateUpdateRequest;
use App\Http\Controllers\RestController;
use App\Http\Requests\RestRequest;
use App\Repositories\ScholarshipRepository;
use App\Repositories\ScholarshipTemplateSubscriptionRepository;
use App\Services\ScholarshipManager;
use App\Transformers\IframeTransformer;
use App\Transformers\ScholarshipTemplateContentTransformer;
use App\Transformers\ScholarshipTemplateFieldTransformer;
use App\Transformers\ScholarshipTemplateRequirementTransformer;
use App\Transformers\ScholarshipTemplateSubscriptionTransformer;
use App\Transformers\ScholarshipTemplateTransformer;
use App\Transformers\ScholarshipTransformer;
use App\Transformers\ScholarshipWebsiteTransformer;
use Doctrine\ORM\EntityManager;
use Illuminate\Http\JsonResponse;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Action\Related\RelatedItemAction;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestResponseFactory;
use Pz\LaravelDoctrine\Rest\Action\CreateAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;

class ScholarshipTemplateController extends RestController
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ScholarshipManager
     */
    protected $sm;

    /**
     * ScholarshipTemplateController constructor.
     * @param EntityManager $em
     * @param ScholarshipManager $sm
     */
    public function __construct(EntityManager $em, ScholarshipManager $sm)
    {
        $this->em = $em;
        $this->sm = $sm;
        $this->repository = RestRepository::create($em, ScholarshipTemplate::class);
        $this->transformer = new ScholarshipTemplateTransformer();
    }

    /**
     * @param ScholarshipTemplateCreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function createScholarship(ScholarshipTemplateCreateRequest $request)
    {
        return (new CreateAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * @param ScholarshipTemplateUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function updateScholarship(ScholarshipTemplateUpdateRequest $request)
    {
        return (new UpdateAction($this->repository(), $this->transformer()))->dispatch($request);
    }

    /**
     * Publish scholarship.
     *
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws RestException
     */
    public function publish(RestRequest $request)
    {
        /** @var ScholarshipTemplate $template */
        $template = $this->repository()->findById($request->getId());

        try {
            $scholarship = $this->sm->publish($template);
        } catch (\Exception $e) {
            throw RestException::createFromException($e);
        }

        return (new RestResponseFactory())
            ->resource($request, new Item($scholarship, new ScholarshipTransformer(), $scholarship->getResourceKey()));
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedIframes(RestRequest $request)
    {
        return (
            new RelatedCollectionAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, Iframe::class),
                new IframeTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedFields(RestRequest $request)
    {
        return (
            new RelatedCollectionAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, ScholarshipTemplateField::class),
                new ScholarshipTemplateFieldTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RelatedFieldsUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedFieldsUpdate(RelatedFieldsUpdateRequest $request)
    {
        return (
            new RelatedFieldsUpdateAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, ScholarshipTemplateField::class),
                new ScholarshipTemplateFieldTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedRequirements(RestRequest $request)
    {
        return (
            new RelatedCollectionAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, ScholarshipTemplateRequirement::class),
                new ScholarshipTemplateRequirementTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RelatedRequirementsUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedRequirementsUpdate(RelatedRequirementsUpdateRequest $request)
    {
        return (
            new RelatedRequirementsUpdateAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, ScholarshipTemplateRequirement::class),
                new ScholarshipTemplateRequirementTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWebsite(RestRequest $request)
    {
        return (
            new RelatedItemAction(
                $this->repository(),
                'website',
                RestRepository::create($this->em, ScholarshipWebsite::class),
                new ScholarshipWebsiteTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param ScholarshipTemplateRelatedWebsiteCreateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWebsiteCreate(ScholarshipTemplateRelatedWebsiteCreateRequest $request)
    {
        return (
            new ScholarshipTemplateRelatedWebsiteUpdateAction(
                $this->repository(),
                RestRepository::create($this->em, ScholarshipWebsite::class),
                new ScholarshipWebsiteTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param ScholarshipTemplateRelatedWebsiteUpdateRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedWebsiteUpdate(ScholarshipTemplateRelatedWebsiteUpdateRequest $request)
    {
        return (
            new ScholarshipTemplateRelatedWebsiteUpdateAction(
                $this->repository(),
                RestRepository::create($this->em, ScholarshipWebsite::class),
                new ScholarshipWebsiteTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedScholarship(RestRequest $request)
    {
        return (
            new RelatedCollectionAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, Scholarship::class),
                new ScholarshipTransformer()
            )
        )
            ->setFilterable(['expiredAt'])
            ->dispatch($request);
    }

    /**
     * @param RelatedSubscriptionRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws RestException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function relatedSubscriptionCreate(RelatedSubscriptionRequest $request)
    {
        /** @var ScholarshipTemplate $template */
        $template = $this->repository()->findById($request->getId());

        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->repository()->getEntityManager()->getRepository(Scholarship::class);

        if ($scholarshipRepository->findSinglePublishedByTemplate($template)) {
            throw RestException::createUnprocessable('Can\'t subscript for active scholarship template.');
        }

        /** @var ScholarshipTemplateSubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->em->getRepository(ScholarshipTemplateSubscription::class);

        $email = $request->input('data.attributes.email');
        if (null === ($templateSubscription = $subscriptionRepository->findOneByTemplateAndEmail($template, $email))) {
            $templateSubscription = new ScholarshipTemplateSubscription();
            $templateSubscription->setStatus(ScholarshipTemplateSubscription::STATUS_WAITING);
            $templateSubscription->setTemplate($template);
            $templateSubscription->setEmail($email);
            $this->em->persist($templateSubscription);
            $this->em->flush($templateSubscription);
        } else {
            $templateSubscription->setStatus(ScholarshipTemplateSubscription::STATUS_WAITING);
            $this->em->flush($templateSubscription);
        }

        return (new RestResponseFactory())->resource($request,
            new Item(
                $templateSubscription,
                new ScholarshipTemplateSubscriptionTransformer(),
                $templateSubscription->getResourceKey()
            )
        );
    }

    /**
     * @param RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     */
    public function relatedContent(RestRequest $request)
    {
        return (
            new RelatedCollectionAction(
                $this->repository(),
                'template',
                RestRepository::create($this->em, ScholarshipTemplateContent::class),
                new ScholarshipTemplateContentTransformer()
            )
        )
            ->dispatch($request);
    }

    /**
     * @param RestRequest $request
     * @return JsonResponse
     */
    public function recurrencePrediction(RestRequest $request)
    {
        $recurrence = ConfigFactory::fromConfig($request->all());

        $result = [];
        $occurrences = empty($recurrence->getOccurrences()) ? 15 : $recurrence->getOccurrences();

        for ($occurrence = 1; $occurrence <= $occurrences; $occurrence++) {
            $result[] = [
                'occurrence' => $occurrence,
                'deadline' => $recurrence->getDeadlineDate(null, $occurrence)->format('c'),
                'start' => $recurrence->getStartDate(null, $occurrence)->format('c'),
            ];
        }

        return JsonResponse::create($result);
    }
}
