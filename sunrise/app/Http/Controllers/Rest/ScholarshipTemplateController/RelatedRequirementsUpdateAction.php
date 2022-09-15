<?php namespace App\Http\Controllers\Rest\ScholarshipTemplateController;

use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipTemplateRequirement;
use Doctrine\Common\Collections\ArrayCollection;
use League\Fractal\TransformerAbstract;
use Pz\Doctrine\Rest\Action\Related\RelatedCollectionAction;
use Pz\Doctrine\Rest\Contracts\RestRequestContract;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestAction;
use Pz\Doctrine\Rest\RestRepository;
use Pz\Doctrine\Rest\RestResponse;
use Pz\Doctrine\Rest\Traits\CanHydrate;
use Pz\Doctrine\Rest\Traits\CanValidate;
use Pz\Doctrine\Rest\Traits\RelatedAction;

class RelatedRequirementsUpdateAction extends RestAction
{
    use RelatedAction;
    use CanHydrate;
    use CanValidate;

    /**
     * RelatedCollectionCreateAction constructor.
     *
     * @param RestRepository                               $repository
     * @param string                                       $mappedBy
     * @param RestRepository                               $related
     * @param \Closure|TransformerAbstract                 $transformer
     */
    public function __construct(RestRepository $repository, $mappedBy, RestRepository $related, $transformer)
    {
        parent::__construct($repository, $transformer);
        $this->mappedBy = $mappedBy;
        $this->related = $related;
    }

    /**
     * @param RestRequestContract $request
     *
     * @return RestResponse
     * @throws RestException
     */
    public function handle($request)
    {
        /** @var ScholarshipTemplate $template */
        $template = $this->repository()->findById($request->getId());

        $this->authorize($request, $template);

        $template->setRequirements(new ArrayCollection());

        foreach ($request->getData() as $raw) {
            /** @var ScholarshipTemplateRequirement $requirement */
            $requirement = $this->hydrateEntity($this->related()->getClassName(), $raw);
            $template->addRequirements($requirement);
        }

        $this->repository()->getEntityManager()->flush($template);

        return (new RelatedCollectionAction(
            $this->repository(),
            $this->mappedBy(),
            $this->related(),
            $this->transformer()
        ))->dispatch($request)->setStatusCode(RestResponse::HTTP_OK);
    }
}
