<?php namespace App\Transformers;

use App\Entities\ScholarshipContent;
use App\Entities\ScholarshipTemplateContent;
use App\Entities\ScholarshipTemplateLog;
use Doctrine\ORM\EntityManager;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use League\Fractal\TransformerAbstract;

class ScholarshipTemplateContentTransformer extends TransformerAbstract
{
    /**
     * @var array
     */
    protected $availableIncludes = ['log'];

    /**
     * @param ScholarshipContent $content
     * @return array
     */
    public function transform(ScholarshipTemplateContent $content)
    {
        return [
            'id' => $content->getId(),
            'type' => $content->getType(),
            'content' => $content->getContent(),
            'createdAt' => $content->getCreatedAt()->format('c'),
            'updatedAt' => $content->getUpdatedAt()->format('c'),
        ];
    }

    /**
     * @param ScholarshipTemplateContent $settings
     * @return \League\Fractal\Resource\Collection
     */
    public function includeLog(ScholarshipTemplateContent $settings)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        /** @var LogEntryRepository $logRepository */
        $logRepository = $em->getRepository(ScholarshipTemplateLog::class);

        /** @var ScholarshipTemplateLog[] $versions */
        $versions = $logRepository->getLogEntriesQuery($settings)
            ->setMaxResults(10)
            ->getResult();

        return $this->collection(
            $versions, new ScholarshipTemplateLogTransformer(), ScholarshipTemplateLog::getResourceKey()
        );
    }
}
