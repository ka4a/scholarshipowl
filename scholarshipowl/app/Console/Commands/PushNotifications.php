<?php

namespace App\Console\Commands;

use App\Events\Firebase\NewMatchEvent;
use Doctrine\ORM\EntityManager;
use Illuminate\Console\Command;


class PushNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushnotification:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Send to Firebase notification about new Matches";


    protected $fetchQuery = "select ec.account_id, ec.eligible_scholarship_ids, ec.last_shown_scholarship_ids from eligibility_cache as ec
                            inner join account a on ec.account_id = a.account_id and a.device_token is not null 
                            where JSON_CONTAINS(ec.eligible_scholarship_ids, ec.last_shown_scholarship_ids) %statement% limit ?, ?";

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * PushNotification constructor.
     *
     * @param EntityManager $em
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
        $this->info(
            sprintf(
                '[%s] Started sending push notification about new matches', date('c')
            )
        );

        $existingCount = $this->sendNewMatchesForExistingEligibilityCacheDiff();
        $initialCount = $this->sendInitialNewMatchPushNotification();

        $this->info(
            sprintf(
                '[%s] Total count of notifications: %s. Initial notifications: %s, for already existing %s', date('c'),
                $existingCount+$initialCount, $initialCount, $existingCount
            )
        );

        $this->info(
                sprintf(
                '[%s] Finished sending push notification about new matches', date('c')
            )
        );
    }

    /**
     *
     * Send push notification to all users who have diff in eligible cache
     * @return int
     */

    protected function sendNewMatchesForExistingEligibilityCacheDiff()
    {
        $count = 0;
        $start = 0;
        $limit = 10000;

        $sql =  str_replace('%statement%', '= 1', $this->fetchQuery);
        while ($result = \DB::select($sql, [$start, $limit])) {
            foreach ($result as $eligibilityCache) {
                $count++;

                $lastShow = json_decode($eligibilityCache->last_shown_scholarship_ids, true);
                $currentScholarships = json_decode($eligibilityCache->eligible_scholarship_ids, true);

                //the list of scholarships that were not showed
                $newScholarships = array_diff_key($currentScholarships, $lastShow);
                \Event::dispatch(new NewMatchEvent($eligibilityCache->account_id, $newScholarships));
            }
            $start += $limit;
            $this->info("Finished part: $start (memory: " . memory_get_usage() . ")");
        }

        return $count;
    }

    /**
     * Send push notification to load all users who don't have last_shown_count at all
     * @return int
     */
    protected function sendInitialNewMatchPushNotification(): int
    {
        $count = 0;
        $start = 0;
        $limit = 10000;

        $sql =  str_replace('%statement%', 'is null', $this->fetchQuery);
        while ($result = \DB::select($sql, [$start, $limit])) {
            foreach ($result as $eligibilityCache) {
                $count++;
                \Event::dispatch(new NewMatchEvent($eligibilityCache->account_id));
            }
            $start += $limit;
            $this->info("Finished part: $start (memory: " . memory_get_usage() . ")");
        }

        return $count;
    }

}

