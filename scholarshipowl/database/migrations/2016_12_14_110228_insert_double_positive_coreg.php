<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDoublePositiveCoreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("coreg_plugins")->insert(array(
            "name" => "DoublePositive",
            "is_visible" => 1,
            "text" => "Select a Program of Interest from our Partners",
            "display_position" => "coreg6"
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'DoublePositive';");
    }
}
