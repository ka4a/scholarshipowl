<?php namespace App\Transformers;

use App\Entities\ApplicationWinner;
use App\Entities\Scholarship;
use App\Entities\ScholarshipField;
use App\Entities\ScholarshipRequirement;
use App\Entities\ScholarshipWebsite;
use App\Traits\HasEntityManager;
use Illuminate\Support\Facades\Gate;
use League\Fractal\Resource\NullResource;
use League\Fractal\TransformerAbstract;

class ScholarshipTransformer extends TransformerAbstract
{
    use HasEntityManager;

    /**
     * @var array
     */
    protected $availableIncludes = [
        'fields',
        'requirements',
        'template',
        'content',
        'website',
        'winners',
        'stats',
    ];

    /**
     * @param Scholarship $scholarship
     * @return array
     * @throws \Exception
     */
    public function transform(Scholarship $scholarship)
    {
        return [
            'id' => $scholarship->getId(),
            'title' => $scholarship->getTitle(),
            'description' => $scholarship->getDescription(),
            'amount' => (int) $scholarship->getAmount(),
            'awards' => $scholarship->getAwards(),
            'status' => $scholarship->getStatus(),

            'scholarshipUrl' => $scholarship->getScholarshipUrl(),
            'scholarshipPPUrl' => $scholarship->getScholarshipPPUrl(),
            'scholarshipTOSUrl' => $scholarship->getScholarshipTOSUrl(),

            /**
             * Convert dates to proper date with timezone.
             */
            'start' => $scholarship->getStart()->format('c'),
            'deadline' => $scholarship->getDeadline()->format('c'),
            'timezone' => $scholarship->getTimezone(),

            /**
             * Recurring settings.
             */
            'recurringValue' => $scholarship->getRecurringValue(),
            'recurringType' => $scholarship->getRecurringType(),

            /**
             * Used to identify YDI scholarship must have "verified email" requirement.
             */
            'isFree' => $scholarship->isIsFree(),
            'isActive' => $scholarship->isActive(),

            'expiredAt' => $scholarship->isExpired() ? $scholarship->getExpiredAt()->format('c') : null,

            'meta' => [
                'next' => $scholarship->getNextDate() ? $scholarship->getNextDate()->format('c') : null,
            ],
        ];
    }

    /**
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Collection
     */
    public function includeFields(Scholarship $scholarship)
    {
        return $this->collection(
            $scholarship->getFields(),
            new ScholarshipFieldTransformer(),
            ScholarshipField::getResourceKey()
        );
    }

    /**
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Item
     */
    public function includeTemplate(Scholarship $scholarship)
    {
        Gate::authorize('restShow', $scholarship->getTemplate());

        return $this->item(
            $scholarship->getTemplate(),
            new ScholarshipTemplateTransformer(),
            $scholarship->getTemplate()->getResourceKey()
        );
    }

    /**
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Item|NullResource
     */
    public function includeContent(Scholarship $scholarship)
    {
        $content = $scholarship->getContent();
        return is_null($content) ? $this->null() : $this->item(
            $scholarship->getContent(),
            new ScholarshipContentTransformer(),
            $scholarship->getContent()->getResourceKey()
        );
    }

    /**
     * Return historical winners for the scholarship.
     *
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Collection
     */
    public function includeWinners(Scholarship $scholarship)
    {
        $winners = $this->em()->getRepository(ApplicationWinner::class)
            ->createQueryBuilder('w')
            ->join('w.application', 'a')
            ->where('a.scholarship = :scholarship')
            ->andWhere('w.phone IS NOT NULL AND w.testimonial IS NOT NULL')
            ->setParameter('scholarship', $scholarship)
            ->getQuery()
            ->getResult();

        return $this->collection($winners, new ApplicationWinnerTransformer(), ApplicationWinner::getResourceKey());
    }

    /**
     * Return current website config.
     *
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeWebsite(Scholarship $scholarship)
    {
        $website = $scholarship->getTemplate()->getWebsite();
        return is_null($website) ? $this->null() : $this->item(
            $website,
            new ScholarshipWebsiteTransformer(),
            ScholarshipWebsite::getResourceKey()
        );
    }

    /**
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Item
     */
    public function includeStats(Scholarship $scholarship)
    {
        return $this->item($scholarship, new ScholarshipStatsTransformer(), 'scholarship_stats');
    }

    /**
     * @param Scholarship $scholarship
     * @return \League\Fractal\Resource\Collection
     */
    public function includeRequirements(Scholarship $scholarship)
    {
        return $this->collection(
            $scholarship->getRequirements(),
            new ScholarshipRequirementTransformer(),
            ScholarshipRequirement::getResourceKey()
        );
    }
}
