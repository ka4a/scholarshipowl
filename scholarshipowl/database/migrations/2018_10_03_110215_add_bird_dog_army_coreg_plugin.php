<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBirdDogArmyCoregPlugin extends Migration
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
            "name" => "BirdDogArmyReserve",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"oid", "value" : "9210"}, {"name" : "bdversion", "value" : "2"}]'
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
            ->where(["name" => "BirddogArmyReserve"])
            ->delete();
    }
}
