<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SchoolGraduationDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('profile', function(Blueprint $table) {
            $table->smallInteger('highschool_graduation_year')->after('graduation_month')->nullable(true);
            $table->tinyInteger('highschool_graduation_month')->after('highschool_graduation_year')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('profile', function(Blueprint $table) {
            $table->dropColumn('highschool_graduation_month');
        });

        \Schema::table('profile', function(Blueprint $table) {
            $table->dropColumn('highschool_graduation_year');
        });
    }
}
