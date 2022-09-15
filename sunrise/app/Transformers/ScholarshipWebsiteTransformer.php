<?php namespace App\Transformers;

use App\Entities\Scholarship;
use App\Entities\ScholarshipWebsite;
use App\Entities\ScholarshipWebsiteFile;
use App\Entities\ScholarshipWinner;
use App\Repositories\ScholarshipRepository;
use App\Traits\HasEntityManager;
use League\Fractal\TransformerAbstract;

class ScholarshipWebsiteTransformer extends TransformerAbstract
{
    use HasEntityManager;

    /**
     * @var array
     */
    protected $defaultIncludes = [
        'logo',
    ];

    /**
     * @var array
     */
    protected $availableIncludes = [
        'template',
        'scholarship',
        'winners',
    ];

    /**
     * @param ScholarshipWebsite $website
     * @return array
     */
    public function transform(ScholarshipWebsite $website)
    {
        return [
            'id' => $website->getId(),
            'domain' => $website->getDomain(),
            'domainHosted' => $website->isDomainHosted(),
            'layout' => $website->getLayout(),
            'variant' => $website->getVariant(),
            'companyName' => $website->getCompanyName(),
            'title' => $website->getTitle(),
            'intro' => $website->getIntro(),
            'gtm' => $website->getGtm(),
            'meta' => [
                'url' => $website->getUrl(),
                'contacts' => [
                    'email' => $website->getTemplate()->getOrganisation()->getEmail(),
                    'phone' => $website->getTemplate()->getOrganisation()->getPhone(),
                ],
            ],
        ];
    }

    /**
     * @param ScholarshipWebsite $website
     * @return \League\Fractal\Resource\Item
     */
    public function includeLogo(ScholarshipWebsite $website)
    {
        return is_null($website->getLogo()) ? $this->null() : $this->item(
            $website->getLogo(),
            new ScholarshipWebsiteFileTransformer(),
            ScholarshipWebsiteFile::getResourceKey()
        );
    }

    /**
     * @param ScholarshipWebsite $website
     * @return \League\Fractal\Resource\Collection
     */
    public function includeWinners(ScholarshipWebsite $website)
    {
        $winners = $this->em()->getRepository(ScholarshipWinner::class)
            ->createQueryBuilder('w')
            ->join('w.scholarship', 's')
            ->join('s.template', 't')
            ->where('t.website = :website')
            ->setParameter('website', $website)
            ->getQuery()
            ->getResult();

        return $this->collection($winners, new ScholarshipWinnerTransformer(), ScholarshipWinner::getResourceKey());
    }

    /**
     * @param ScholarshipWebsite $website
     * @return \League\Fractal\Resource\Item|\League\Fractal\Resource\NullResource
     */
    public function includeScholarship(ScholarshipWebsite $website)
    {
        /** @var ScholarshipRepository $scholarshipRepository */
        $scholarshipRepository = $this->em()->getRepository(Scholarship::class);

        $scholarship = $scholarshipRepository->findSinglePublishedByTemplate($website->getTemplate());

        if (is_null($scholarship)) {
            return $this->null();
        }

        return $this->item($scholarship, new ScholarshipTransformer(), $scholarship->getResourceKey());
    }

    /**
     * @param ScholarshipWebsite $website
     * @return \League\Fractal\Resource\Item
     */
    public function includeTemplate(ScholarshipWebsite $website)
    {
        return $this->item(
            $website->getTemplate(),
            new ScholarshipTemplateTransformer(),
            $website->getTemplate()->getResourceKey()
        );
    }
}
