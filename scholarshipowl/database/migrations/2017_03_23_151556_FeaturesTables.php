<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FeaturesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        \Schema::create('feature_payment_set', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('popup_title');
            $table->text('packages');
            $table->timestamps();

            $table->unique('name');
        });

        \Schema::create('feature_set', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unique('name');
            $table->unsignedInteger('desktop_payment_set');
            $table->unsignedInteger('mobile_payment_set');
            $table->timestamps();

            $table->foreign('desktop_payment_set')
                ->references('id')
                ->on('feature_payment_set');
            $table->foreign('mobile_payment_set')
                ->references('id')
                ->on('feature_payment_set');
        });

        \DB::table('feature_payment_set')->insert([[
            'name' => 'Default Desktop',
            'popup_title' => '[[first_name]], Get a membership for Free for 7 days to activate automatic application to your [[eligible_scholarships_count]] scholarship matches. 
Let us do the hard work for you!',
            'packages' => '[{"id":59},{"id":72},{"id":70,"flag":"1"},{"id":71}]',
        ],[
            'name' => 'Default Mobile',
            'popup_title' => '[[first_name]], upgrade and you could get applied to all your scholarship matches automatically now. ',
            'packages' => '[{"id":72,"flag":"1"},{"id":70},{"id":71},{"id":61}]',
        ]
        ]);

        \DB::table('feature_set')->insert([[
            'name' => 'Default',
            'desktop_payment_set' => 1,
            'mobile_payment_set' => 2,
        ]]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('feature_set');
        \Schema::dropIfExists('feature_payment_set');
    }
}
