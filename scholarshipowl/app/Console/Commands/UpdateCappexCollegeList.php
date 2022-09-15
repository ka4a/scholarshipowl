<?php

namespace App\Console\Commands;

use Curl\Curl;
use Illuminate\Console\Command;

class UpdateCappexCollegeList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cappex:update";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update list of colleges in cappex_college table from cappex API";

    /**
     * The API url.
     *
     * @var string
     */
    protected $apiUrl = "http://www.uat.aws.cappex.com/api/colleges?key=scholarship-owl&programId=5884930";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Cappex Update command started at : " . date("Y-m-d h:i:s"));

        $curl = new Curl();

        $curl->get($this->apiUrl);

        $response = $curl->response;

        $foundIds = array();

        foreach ($response as $item){
            if(\DB::table("cappex_college")->where("cappex_college_id", $item->collegeData->collegeId)->first()){
                \DB::table("cappex_college")
                    ->where("cappex_college_id", $item->collegeData->collegeId)
                    ->update(
                        ["cappex_college_name" => $item->collegeData->collegeName]
                    );
            }else{
                \DB::table("cappex_college")
                    ->insert(
                        ["cappex_college_id" => $item->collegeData->collegeId, "cappex_college_name" => $item->collegeData->collegeName]
                    );
            }
            $foundIds[] = $item->collegeData->collegeId;
        }

        \DB::table("cappex_college")
        ->whereNotIn("cappex_college_id", $foundIds)
            ->delete();

        $this->info("Cappex Update command ended at : " . date("Y-m-d h:i:s"));
    }
}
