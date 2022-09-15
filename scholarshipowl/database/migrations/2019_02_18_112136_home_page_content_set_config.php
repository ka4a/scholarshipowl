<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HomePageContentSetConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->boolean('hp_double_promotion_flag')
                ->default(0);
            $table->boolean('hp_ydi_flag')
                ->default(0);
            $table->string('hp_cta_text')
                ->default("CHECK FOR SCHOLARSHIPS");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('feature_content_set', function(Blueprint $table) {
            $table->dropColumn('hp_double_promotion_flag');
            $table->dropColumn('hp_ydi_flag');
            $table->dropColumn('hp_cta_text');
        });
    }
}
