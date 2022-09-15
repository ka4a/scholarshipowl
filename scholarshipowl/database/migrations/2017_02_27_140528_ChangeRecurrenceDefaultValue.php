<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRecurrenceDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `profile` 
            CHANGE COLUMN `recurring_application` `recurring_application` TINYINT(3) UNSIGNED NOT NULL DEFAULT '2' COMMENT '' ;
        ");
        \DB::statement('UPDATE `profile` SET `recurring_application` = 2 WHERE `recurring_application` = 0;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `profile` 
            CHANGE COLUMN `recurring_application` `recurring_application` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '' ;
        ");
    }
}
