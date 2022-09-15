<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SuperCollegeScholarshipMatch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('super_college_scholarship_match', function (Blueprint $table) {
            $table->integer('account_id', false, true);
            $table->integer('super_college_scholarship_id', false, true);
            $table->dateTime('match_date');

            $table->primary(['account_id', 'super_college_scholarship_id'], 'super_college_scholarship_match_primary');
            $table->index('match_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('super_college_scholarship_match');
    }
}
