<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ScholarshipRecurrenceSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('scholarship', function(Blueprint $table) {
            $table->boolean('recurrence_start_now')->default(false);
            $table->boolean('recurrence_end_month')->default(false);
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
            $table->dropColumn('recurrence_end_month');
            $table->dropColumn('recurrence_start_now');
        });
    }
}
