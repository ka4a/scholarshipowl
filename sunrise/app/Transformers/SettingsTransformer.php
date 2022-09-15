<?php namespace App\Transformers;

use App\Entities\Settings;
use App\Entities\SettingsLog;
use Doctrine\ORM\EntityManager;
use Gedmo\Loggable\Entity\Repository\LogEntryRepository;
use League\Fractal\TransformerAbstract;

class SettingsTransformer extends TransformerAbstract
{
    const LOG_RESULT_LIMIT = 10;

    /**
     * @var array
     */
    protected $availableIncludes = ['log'];

    /**
     * @param Settings $settings
     * @return array
     */
    public function transform(Settings $settings)
    {
        return [
            'id' => $settings->getId(),
            'name' => $settings->getName(),
            'config' => $settings->getConfig(),
            'createdAt' => $settings->getCreatedAt()->format('c'),
            'updatedAt' => $settings->getUpdatedAt()->format('c'),
        ];
    }

    /**
     * @param Settings $settings
     * @return \League\Fractal\Resource\Collection
     */
    public function includeLog(Settings $settings)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);

        /** @var LogEntryRepository $logRepository */
        $logRepository = $em->getRepository(SettingsLog::class);

        /** @var SettingsLog[] $versions */
        $versions = $logRepository->getLogEntriesQuery($settings)
            ->setMaxResults(10)
            ->getResult();

        return $this->collection($versions, new SettingsLogTransformer(), SettingsLog::getResourceKey());
    }
}
