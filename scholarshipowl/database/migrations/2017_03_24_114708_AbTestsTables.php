<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AbTestsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        \Schema::create('feature_ab_test', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unique('name');

            $table->boolean('enabled')->default(false);
            $table->unsignedInteger('feature_set');
            $table->text('config');

            $table->timestamps();

            $table->foreign('feature_set')
                ->references('id')
                ->on('feature_set');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('feature_ab_test');
    }
}
