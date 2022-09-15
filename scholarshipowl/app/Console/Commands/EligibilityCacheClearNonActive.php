<?php

namespace App\Console\Commands;

use App\Entity\EligibilityCache;
use App\Entity\Repository\EligibilityCacheRepository;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;

class EligibilityCacheClearNonActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eligibilitycache:clear-nonactive';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Remove records from Eligibility Cache for user who don't have subscription";

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $this->info("Clear Eligibility Cache for inactive and without subscription accounts. Start: " . date("Y-m-d h:i:s"));

        /**
         * @var EligibilityCacheRepository $elbRepository
         */
        $elbRepository = $this->em->getRepository(EligibilityCache::class);
        $deletedCnt = $elbRepository->removeStaleCache(7);

        $this->info("Deleted $deletedCnt records from eligibility cache table");
        $this->info("Clear Eligibility Cache for users without subscription. Finish: " . date("Y-m-d h:i:s"));
    }
}
