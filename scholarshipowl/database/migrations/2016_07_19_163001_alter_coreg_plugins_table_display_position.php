<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoregPluginsTableDisplayPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            ALTER TABLE `coreg_plugins` 
            CHANGE COLUMN `display_position` `display_position` VARCHAR(45) NULL DEFAULT 'none' COMMENT 'Column stores display positions for front end rendering.' ;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
