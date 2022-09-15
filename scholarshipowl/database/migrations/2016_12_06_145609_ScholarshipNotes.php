<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("
            ALTER TABLE `scholarship_owl`.`scholarship` 
            CHANGE COLUMN `description` `description` VARCHAR(2047) NULL DEFAULT NULL,
            CHANGE COLUMN `email_message` `email_message` VARCHAR(2047) NULL DEFAULT NULL,
            CHANGE COLUMN `form_method` `form_method` VARCHAR(8) NULL DEFAULT NULL;
        ");

        \Schema::table('scholarship', function(Blueprint $table) {
            $table->string('notes')->default('')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('scholarship', function(Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
}
