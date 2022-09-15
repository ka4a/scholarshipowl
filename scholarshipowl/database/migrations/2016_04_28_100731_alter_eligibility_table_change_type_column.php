<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterEligibilityTableChangeTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(
            "ALTER TABLE `eligibility` 
                CHANGE COLUMN `type` `type` ENUM('required', 'value', 'less_than', 'greater_than', 'between', 'not', 'in') NOT NULL DEFAULT 'required' COMMENT 'Eligibility type.' ;"
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(
            "ALTER TABLE `eligibility` 
                CHANGE COLUMN `type` `type` ENUM('required', 'value', 'less_than', 'greater_than', 'between', 'not') NOT NULL DEFAULT 'required' COMMENT 'Eligibility type.' ;"
        );
    }
}
