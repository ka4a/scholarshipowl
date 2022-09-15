<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ThankYouPageBanners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::dropIfExists('banner');
        \Schema::create('banner', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('url')->nullable();
            $table->unsignedTinyInteger('type');
            $table->text('header_content')->nullable();
            $table->text('text')->nullable();
            $table->timestamps();
        });

        \Schema::dropIfExists('banner_image');
        \Schema::create('banner_image', function(Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('banner_id');
            $table->foreign('banner_id')->references('id')->on('banner');
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('banner');
        \Schema::dropIfExists('banner_image');
    }
}
