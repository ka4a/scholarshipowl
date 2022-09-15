<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        \Schema::create('page', function(Blueprint $table) {
            $table->increments('id');

            $table->string('path');
            $table->unique('path');

            $table->unsignedSmallInteger('type');

            $table->text('title')->nullable();
            $table->string('description')->nullable();
            $table->string('keywords')->nullable();
            $table->text('author')->nullable();

            $table->timestamps();
        });

        \Schema::create('page_offer_wall', function(Blueprint $table) {
            $table->unsignedInteger('page_id');
            $table->primary('page_id');
            $table->foreign('page_id')->references('id')->on('page');

            $table->text('title');
            $table->text('description');

            $table->unsignedInteger('banner1')->nullable();
            $table->foreign('banner1')->references('id')->on('banner');
            $table->unsignedInteger('banner2')->nullable();
            $table->foreign('banner2')->references('id')->on('banner');
            $table->unsignedInteger('banner3')->nullable();
            $table->foreign('banner3')->references('id')->on('banner');
            $table->unsignedInteger('banner4')->nullable();
            $table->foreign('banner4')->references('id')->on('banner');
            $table->unsignedInteger('banner5')->nullable();
            $table->foreign('banner5')->references('id')->on('banner');
            $table->unsignedInteger('banner6')->nullable();
            $table->foreign('banner6')->references('id')->on('banner');

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
        \Schema::dropIfExists('page_offer_wall');
        \Schema::dropIfExists('page');
    }
}
