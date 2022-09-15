<?php namespace App\Jobs;

use App\Entity\Application;
use App\Entity\Counter;
use App\Entity\Repository\ApplicationRepository;

class ApplicationCountJob extends Job
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $count = $this->getRepository()
            ->getSuccessfulApplicationsQuery()
            ->getQuery()
            ->getSingleScalarResult();

        $counter = Counter::findByName("application");
        $counter->setCount($count);

        \EntityManager::flush();
    }

    /**
     * @return ApplicationRepository
     */
    protected function getRepository()
    {
        return \EntityManager::getRepository(Application::class);
    }
}
