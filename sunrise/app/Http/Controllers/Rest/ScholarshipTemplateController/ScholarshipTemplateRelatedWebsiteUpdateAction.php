<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipWebsite;
use League\Fractal\Resource\Item;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\Traits\CanHydrate;
use Pz\Doctrine\Rest\Traits\RelatedAction;
use Pz\LaravelDoctrine\Rest\Action\UpdateAction;

class ScholarshipTemplateRelatedWebsiteUpdateAction extends UpdateAction
{
    use RelatedAction;
    use CanHydrate;

    /**
     * ScholarshipTemplateRelatedWebsiteUpdateAction constructor.
     *
     * @param RestRepository $repository
     * @param RestRepository $related
     * @param $transformer
     */
    public function __construct(RestRepository $repository, RestRepository $related, $transformer)
    {
        parent::__construct($repository, $transformer);
        $this->related = $related;
    }

    /**
     * @param \Pz\Doctrine\Rest\RestRequest $request
     * @return \Pz\Doctrine\Rest\RestResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Pz\Doctrine\Rest\Exceptions\RestException
     */
    public function handle($request)
    {
        /** @var ScholarshipTemplate $template */
        $template = $this->repository()->findById($request->getId());

        $this->authorize($request, $template);

        /** @var ScholarshipWebsite $website */
        if (null === ($website = $template->getWebsite())) {
            $website = new ScholarshipWebsite();
            $this->repository()->getEntityManager()->persist($website);
        }

        $website = $this->hydrateEntity($website, $request->getData());

        $template->setWebsite($website);

        $this->repository()->getEntityManager()->flush();

        return $this->response()->resource($request,
            new Item($website, $this->transformer(), $this->related()->getResourceKey())
        );
    }
}
