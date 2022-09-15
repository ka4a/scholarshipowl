<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBirdDogMaleCoreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $displayPosition = "coreg6a";
        $linkText = "Get more information about forging your future with America’s Navy.";
        \DB::table("coreg_plugins")->insert([
            "name" => "BirdDogMale",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition,
            "extra" => '[{"name":"offer_id", "value" : "1244625"}, {"name": "oid", "value" : "9335"}]'
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
            ->where(["name" => "BirdDogMale"])
            ->delete();
    }
}
