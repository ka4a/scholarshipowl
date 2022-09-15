<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeatureBannersSets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('feature_banner_set', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unique('name');
            $table->timestamps();
        });

        \Schema::create('feature_banner_set_banner', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('feature_banner_set_id');
            $table->unsignedInteger('banner_id');
            $table->index('feature_banner_set_id');

            $table->foreign('feature_banner_set_id', 'fk_feature_banner_set_banner_feature_set_banner')
                ->references('id')->on('feature_banner_set');

            $table->foreign('banner_id', 'fk_feature_banner_set_banner_banner_id')
                ->references('id')->on('banner');

            $table->timestamps();
        });

        \Schema::table('feature_set', function(Blueprint $table) {
            $table->unsignedInteger('jobs_banner_set')->after('content_set')->nullable();

            $table->foreign('jobs_banner_set', 'fk_feature_set_jobs_banner_set')
                ->references('id')->on('feature_banner_set');
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
            $table->dropForeign('fk_feature_set_jobs_banner_set');
            $table->dropColumn('jobs_banner_set');
        });

        \Schema::dropIfExists('feature_banner_set_banner');
        \Schema::dropIfExists('feature_banner_set');
    }
}
