<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CoregPluginJustCollectFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('coreg_plugins', function(Blueprint $table) {
            $table->boolean('just_collect');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('coreg_plugins', function (Blueprint $table) {
            $table->dropColumn('just_collect');
        });
    }
}
