<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SunriseRequirementPermanentId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->unsignedInteger('external_id_permanent')->after('external_id')->index()->nullable(true);
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->unsignedInteger('external_id_permanent')->after('external_id')->index()->nullable(true);
        });

        \Schema::table('requirement_file', function(Blueprint $table) {
            $table->unsignedInteger('external_id_permanent')->after('external_id')->index()->nullable(true);
        });

        \Schema::table('requirement_image', function(Blueprint $table) {
            $table->unsignedInteger('external_id_permanent')->after('external_id')->index()->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->dropColumn('external_id_permanent');
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->dropColumn('external_id_permanent');
        });

        \Schema::table('requirement_file', function(Blueprint $table) {
            $table->dropColumn('external_id_permanent');
        });

        \Schema::table('requirement_image', function(Blueprint $table) {
            $table->dropColumn('external_id_permanent');
        });
    }
}
