<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCoregRequirementsRuleSet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('coreg_requirements_rule_set', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('table_name');
        });

        \Schema::create('coreg_requirements_rule', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('coreg_requirements_rule_set_id');
            $table->string('field');
            $table->string('operator');
            $table->string('value');
            $table->tinyInteger('active');
            $table->tinyInteger('send');
        });

        \Schema::create('coreg_resubmission_tries', function(Blueprint $table) {
            $table->integer('submission_id');
            $table->integer('tries');
            $table->dateTime('last_update');
            $table->primary('submission_id');
        });

        \Schema::table('coreg_plugins', function(Blueprint $table) {
            $table->integer('coreg_requirements_rule_set_id');
        });

        \Schema::table('submission', function(Blueprint $table) {
            $table->integer('source');
        });

        \Schema::create('submission_sources', function(Blueprint $table) {
            $table->increments('id');
            $table->string('source');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('coreg_requirements_rule_set');
        \Schema::dropIfExists('coreg_requirements_rule');
        \Schema::dropIfExists('coreg_resubmission_tries');

        \Schema::table('coreg_plugins', function (Blueprint $table) {
            $table->dropColumn('coreg_requirements_rule_set_id');
        });
        \Schema::table('submission', function (Blueprint $table) {
            $table->dropColumn('source');
        });

        \Schema::dropIfExists('submission_sources');

    }
}
