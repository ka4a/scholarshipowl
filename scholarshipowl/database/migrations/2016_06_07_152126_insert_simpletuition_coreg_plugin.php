<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertSimpletuitionCoregPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			INSERT INTO `coreg_plugins` (`name`, `is_visible`, `text`, `display_position`) VALUES ('SimpleTuition', '1', 'Send me information on private student loan options via SimpleTuition', 'coreg5');
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'SimpleTuition';");
    }
}
