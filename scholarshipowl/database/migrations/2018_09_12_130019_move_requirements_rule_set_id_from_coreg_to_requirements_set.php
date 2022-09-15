<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MoveRequirementsRuleSetIdFromCoregToRequirementsSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('coreg_requirements_rule_set', function(Blueprint $table) {
            $table->integer('coreg_id')->nullable();
        });

        \Schema::table('coreg_plugins', function (Blueprint $table) {
            $table->dropColumn('coreg_requirements_rule_set_id');
        });

        \Schema::table('coreg_requirements_rule', function(Blueprint $table) {
            $table->renameColumn('active', 'is_show_rule');
            $table->renameColumn('send', 'is_send_rule');
        });


        \DB::statement("
            UPDATE submission
            SET source = 1
            where name  REGEXP BINARY '^[A-Z]';
        ");
        \DB::statement("
            UPDATE submission
            SET source = 2
            where name  REGEXP BINARY '^[^A-Z]';
        ");
        \DB::statement("
            UPDATE submission
            SET source = 3
            where name = 'Vinyl';
        ");
        // Just in case we missed something
        \DB::statement("
            UPDATE submission
            SET source = 1
            where source = 0 or source IS NULL;
        ");
        Schema::table('submission_sources', function(Blueprint $table) {
            $table->smallInteger('id')->change();
        });

        Schema::table('submission', function(Blueprint $table) {
            $table->smallInteger('source')->default("1")->change();
            $table->foreign('source')->references('id')->on('submission_sources');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('coreg_requirements_rule_set', function (Blueprint $table) {
            $table->dropColumn('coreg_id');
        });
    }
}
