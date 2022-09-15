<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOptionRequirementFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('requirement_file', function (Blueprint $table) {
            $table->tinyInteger('is_optional')->default(0);
        });

        \Schema::table('requirement_image', function (Blueprint $table) {
            $table->tinyInteger('is_optional')->default(0);
        });

        \Schema::table('requirement_input', function (Blueprint $table) {
            $table->tinyInteger('is_optional')->default(0);
        });

        \Schema::table('requirement_text', function (Blueprint $table) {
            $table->tinyInteger('is_optional')->default(0);
        });

        \Schema::table('requirement_special_eligibility', function (Blueprint $table) {
            $table->tinyInteger('is_optional')->default(0);
        });

        \Schema::table('requirement_survey', function (Blueprint $table) {
            $table->tinyInteger('is_optional')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('requirement_file', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });

        \Schema::table('requirement_image', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });

        \Schema::table('requirement_input', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });

        \Schema::table('requirement_text', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });

        \Schema::table('requirement_special_eligibility', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });

        \Schema::table('requirement_survey', function (Blueprint $table) {
            $table->dropColumn('is_optional');
        });

    }
}
