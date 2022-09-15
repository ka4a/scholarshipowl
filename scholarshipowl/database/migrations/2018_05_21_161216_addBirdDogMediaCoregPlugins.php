<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBirdDogMediaCoregPlugins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $displayPosition = "coreg6a";
        $linkText = "Get more information about forging your future with American’s Navy. Terms and Conditions <a target='_blank' href='https://www.navy.com/privacy-policy'>here</a>.";
        \DB::table("coreg_plugins")->insert([
            "name" => "BirdDogAA",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"offer_id", "value" : "1229683"}, {"name": "oid", "value" : "9095"}]'
        ]);

        \DB::table("coreg_plugins")->insert([
            "name" => "BirdDogAsian",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"offer_id", "value" : "1229685"}, {"name": "oid", "value" : "9096"}]'
        ]);

        \DB::table("coreg_plugins")->insert([
            "name" => "BirdDogHispanic",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"offer_id", "value" : "1229684"}, {"name": "oid", "value" : "9099"}]'
        ]);

        \DB::table("coreg_plugins")->insert([
            "name" => "BirdDogFemale",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"offer_id", "value" : "1229686"}, {"name": "oid", "value" : "9098"}]'
        ]);

        \DB::table("coreg_plugins")->insert([
            "name" => "BirdDogNUPOC",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"offer_id", "value" : "1229682"}, {"name" : "oid", "value" : "9101"}]'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table("coreg_plugins")
            ->where(["name" => "BirdDogAA"])
            ->delete();

        \DB::table("coreg_plugins")
            ->where(["name" => "BirdDogAsia"])
            ->delete();

        \DB::table("coreg_plugins")
            ->where(["name" => "BirdDogHispanic"])
            ->delete();

        \DB::table("coreg_plugins")
            ->where(["name" => "BirdDogFemale"])
            ->delete();

        \DB::table("coreg_plugins")
            ->where(["name" => "BirdDogNUPOC"])
            ->delete();

    }
}
