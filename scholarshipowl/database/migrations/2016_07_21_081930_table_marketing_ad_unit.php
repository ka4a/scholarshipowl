<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableMarketingAdUnit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_ad_unit', function (Blueprint $table) {
            
            $table->increments('marketing_ad_unit_id');
            $table->string('url', 255)->nullable();
            $table->boolean('is_enabled');
            $table->string('description', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('marketing_ad_unit');
    }
}
