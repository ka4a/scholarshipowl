<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCoregPluginsTableAddCapping extends Migration
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
            ADD COLUMN `monthly_cap` INT(11) UNSIGNED NULL DEFAULT NULL AFTER `redirect_rules_set_id`;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("
            ALTER TABLE `coreg_plugins` 
            DROP COLUMN `monthly_cap`;
        ");
    }
}
