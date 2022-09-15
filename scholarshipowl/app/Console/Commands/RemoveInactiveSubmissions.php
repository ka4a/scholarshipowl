<?php

namespace App\Console\Commands;

use App\Entity\Marketing\Submission;
use Carbon\Carbon;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class RemoveInactiveSubmissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "submission:remove-inactive {days=30}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Remove old inactive submissions.";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = $this->argument("days");

        $q = $this->em->createQuery("delete from App\Entity\Marketing\Submission s where s.updatedAt < :date AND s.status = :status")->setParameter("date",
            Carbon::now()->subDays($days))->setParameter("status", Submission::STATUS_INCOMPLETE);
        $numDeleted = $q->execute();

        $this->info("Deleted: " . $numDeleted . " submissions");

        return;
    }
}
