<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubmissionAddCoregPluginId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('submission', function (Blueprint $table) {
            $table->integer('coreg_plugin_id')->unsigned()->nullable();
            $table->foreign('coreg_plugin_id')->references('coreg_plugin_id')->on('coreg_plugins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('submission', function (Blueprint $table) {
            $table->dropForeign('submission_coreg_plugin_id_foreign');
            $table->dropColumn('coreg_plugin_id');
        });
    }
}
