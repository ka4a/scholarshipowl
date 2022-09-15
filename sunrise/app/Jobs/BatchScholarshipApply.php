<?php namespace App\Jobs;

use App\Doctrine\QueryIterator;
use App\Entities\Application;
use App\Entities\ApplicationBatch;
use App\Entities\Scholarship;
use App\Repositories\ScholarshipRepository;
use App\Services\ApplicationService;
use App\Services\ScholarshipManager;

use Doctrine\ORM\EntityManager;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Apply to all eligible scholarship that fields match the eligibility.
 */
class BatchScholarshipApply implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var int
     */
    protected $batchId;

    /**
     * @var array
     */
    protected $scholarships;

    /**
     * BatchScholarshipApply constructor.
     *
     * @param ApplicationBatch|int $batch
     * @param array $scholarships
     */
    public function __construct($batch, array $scholarships = null)
    {
        $this->batchId = ($batch instanceof ApplicationBatch) ? $batch->getId() : $batch;
        $this->scholarships = $scholarships;
    }

    /**
     * @param EntityManager $em
     * @param ApplicationService $service
     * @throws \Exception
     */
    public function handle(EntityManager $em, ApplicationService $service)
    {
        /** @var ApplicationBatch $batch */
        $batch = $em->find(ApplicationBatch::class, $this->batchId);
        $batch->setStatus(ApplicationBatch::STATUS_RUNNING);
        $em->flush($batch);

        /** @var ScholarshipRepository $repository */
        $repository = $em->getRepository(Scholarship::class);

        $eligibleQuery = empty($this->scholarships) ? null : $repository
            ->createQueryBuilder('s')
            ->where('s.id IN (:filter)')
            ->setParameter('filter', $this->scholarships)
            ->getQuery();

        $iterator = QueryIterator::create($repository->queryEligible($batch->getData(), $eligibleQuery), 100);
        $applied = 0;
        $errors = 0;

        /** @var Scholarship[] $scholarships */
        foreach ($iterator as $scholarships) {
            foreach ($scholarships as $scholarship) {
                try {
                    $application = $service->apply($scholarship, $batch->getData());
                    $application->setSource($batch->getSource());
                    $batch->addApplications($application);
                    $applied++;
                } catch (\Exception $e) {
                    if (app()->environment('testing')) {
                        throw $e;
                    }
                    $errors++;
                    report($e);
                }
            }
            $em->clear(Scholarship::class);
        }

        $batch->setStatus(ApplicationBatch::STATUS_FINISHED);
        $batch->setApplied($applied);
        $batch->setErrors($errors);
        $em->flush($batch);
    }
}
