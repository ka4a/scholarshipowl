<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBirdDogCoregCampaignIds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1235061"}, {"name": "oid", "value" : "9095"}]\' WHERE name = "BirdDogAA"');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1235063"}, {"name": "oid", "value" : "9096"}]\' WHERE name = "BirdDogAsian"');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1235062"}, {"name": "oid", "value" : "9099"}]\' WHERE name = "BirdDogHispanic"');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1235064"}, {"name": "oid", "value" : "9098"}]\' WHERE name = "BirdDogFemale"');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1235060"}, {"name" : "oid", "value" : "9101"}]\' WHERE name = "BirdDogNUPOC"');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1229683"}, {"name": "oid", "value" : "9095"}]\' WHERE coreg_plugin_id = 17');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1229685"}, {"name": "oid", "value" : "9096"}]\' WHERE coreg_plugin_id = 18');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1229684"}, {"name": "oid", "value" : "9099"}]\' WHERE coreg_plugin_id = 19');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1229686"}, {"name": "oid", "value" : "9098"}]\' WHERE coreg_plugin_id = 20');
        DB::update('UPDATE coreg_plugins SET extra = \'[{"name":"offer_id", "value" : "1229682"}, {"name" : "oid", "value" : "9101"}]\' WHERE coreg_plugin_id = 21');
    }
}
