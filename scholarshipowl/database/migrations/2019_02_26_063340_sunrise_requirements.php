<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SunriseRequirements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('requirement_text', function(Blueprint $table) {
            $table->unsignedInteger('external_id')->after('id')->index()->nullable(true);
            $table->unique(['id', 'external_id']);
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->unsignedInteger('external_id')->after('id')->index()->nullable(true);
            $table->unique(['id', 'external_id']);
        });

        \Schema::table('requirement_file', function(Blueprint $table) {
            $table->unsignedInteger('external_id')->after('id')->index()->nullable(true);
            $table->unique(['id', 'external_id']);
        });

        \Schema::table('requirement_image', function(Blueprint $table) {
            $table->unsignedInteger('external_id')->after('id')->index()->nullable(true);
            $table->unique(['id', 'external_id']);
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
            $table->dropUnique(['id', 'external_id']);
            $table->dropColumn('external_id');
        });

        \Schema::table('requirement_input', function(Blueprint $table) {
            $table->dropUnique(['id', 'external_id']);
            $table->dropColumn('external_id');
        });

        \Schema::table('requirement_file', function(Blueprint $table) {
            $table->dropUnique(['id', 'external_id']);
            $table->dropColumn('external_id');
        });

        \Schema::table('requirement_image', function(Blueprint $table) {
            $table->dropUnique(['id', 'external_id']);
            $table->dropColumn('external_id');
        });
    }
}
