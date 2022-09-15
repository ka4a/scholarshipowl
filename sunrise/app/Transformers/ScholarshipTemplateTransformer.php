<?php namespace App\Transformers;

use App\Entities\Iframe;
use App\Entities\Organisation;
use App\Entities\Scholarship;
use App\Entities\ScholarshipTemplate;
use App\Entities\ScholarshipTemplateField;
use App\Entities\ScholarshipTemplateRequirement;
use App\Entities\ScholarshipWebsite;
use App\Repositories\IframeRepository;
use App\Repositories\ScholarshipRepository;
use App\Traits\HasEntityManager;
use Doctrine\ORM\EntityManager;
use League\Fractal\TransformerAbstract;

class ScholarshipTemplateTransformer extends TransformerAbstract
{
    use HasEntityManager;

    /**
     * @var array
     */
    protected $availableIncludes = [
        'fields',
        'requirements',
        'website',
        'published',
        'organisation',
        'iframes',
    ];

    /**
     * @param ScholarshipTemplate $template
     * @return array
     */
    public function transform(ScholarshipTemplate $template)
    {
        return [
            'id' => $template->getId(),
            'title' => $template->getTitle(),
            'description' => $template->getDescription(),
            'amount' => (int) $template->getAmount(),
            'awards' => $template->getAwards(),

            'scholarshipUrl' => $template->getScholarshipUrl(),
            'scholarshipPPUrl' => $template->getScholarshipPPUrl(),
            'scholarshipTOSUrl' => $template->getScholarshipTOSUrl(),

            /**
             * Convert dates to proper date with timezone.
             */
            'timezone' => $template->getTimezone(),
            'recurrenceConfig' => $template->getRecurrenceConfig() ?
                $template->getRecurrenceConfig()->toArray() : null,

            /**
             * Used to identify YDI scholarship
             */
            'isFree' => $template->isIsFree(),

            /**
             * Is scholarship recurrence paused.
             */
            'paused' => $template->isPaused(),
        ];
    }

    /**
     * @param ScholarshipTemplate $template
     * @return \League\Fractal\Resource\Item
     */
    public function includeOrganisation(ScholarshipTemplate $template)
    {
        return $this->item(
            $template->getOrganisation(),
            new OrganisationTransformer(),
            Organisation::getResourceKey()
        );
    }

    /**
     * @param ScholarshipTemplate $template
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFields(ScholarshipTemplate $template)
    {
        return $this->collection(
            $template->getFields(),
            new ScholarshipTemplateTransformer(),
            ScholarshipTemplateField::getResourceKey()
        );
    }

    /**
     * Template relationship.
     *
     * @param ScholarshipTemplate $template
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeWebsite(ScholarshipTemplate $template)
    {
        if (is_null($template->getWebsite())) {
            return $this->null();
        }

        return $this->item(
            $template->getWebsite(),
            new ScholarshipWebsiteTransformer(),
            ScholarshipWebsite::getResourceKey()
        );
    }

    /**
     * @param ScholarshipTemplate $template
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function includePublished(ScholarshipTemplate $template)
    {
        /** @var ScholarshipRepository $repository */
        $repository = $this->em()->getRepository(Scholarship::class);
        $published = $repository->findSinglePublishedByTemplate($template);

        if (is_null($published)) {
            return $this->null();
        }

        return $this->item(
            $published,
            new ScholarshipTransformer(),
            $published->getResourceKey()
        );
    }

    /**
     * @param ScholarshipTemplate $template
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRequirements(ScholarshipTemplate $template)
    {
        return $this->collection(
            $template->getRequirements(),
            new ScholarshipTemplateRequirementTransformer(),
            ScholarshipTemplateRequirement::getResourceKey()
        );
    }

    /**
     * @param ScholarshipTemplate $template
     * @return \League\Fractal\Resource\Collection
     */
    public function includeIframes(ScholarshipTemplate $template)
    {
        /** @var IframeRepository $repository */
        $repository = app(EntityManager::class)->getRepository(Iframe::class);

        return $this->collection(
            $repository->findByTemplate($template),
            new IframeTransformer(),
            Iframe::getResourceKey()
        );
    }
}
