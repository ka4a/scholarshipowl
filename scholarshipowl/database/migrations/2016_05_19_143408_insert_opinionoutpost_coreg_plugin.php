<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertOpinionoutpostCoregPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
			INSERT INTO `coreg_plugins` (`name`, `is_visible`, `text`, `display_position`) VALUES ('Opinionoutpost', '1', ' Get info on the OpinionOutpost $10,000 sweepstakes', 'coreg2');
		");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'OpinionOutpost';");
    }

}
