<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveFeatureSetJobsBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_set', function(Blueprint $table) {
            $table->dropForeign('fk_feature_set_jobs_banner_set');
            $table->dropColumn('jobs_banner_set');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_set', function(Blueprint $table) {
            $table->unsignedInteger('jobs_banner_set')->after('content_set')->nullable();

            $table->foreign('jobs_banner_set', 'fk_feature_set_jobs_banner_set')
                ->references('id')->on('feature_banner_set');
        });
    }
}
