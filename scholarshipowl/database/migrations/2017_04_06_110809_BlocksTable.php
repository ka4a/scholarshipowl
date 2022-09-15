<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('feature_block', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unique('name');

            $table->text('text');
            $table->timestamps();
        });

        \DB::table('feature_block')->insert([[
            'name' => 'Home page default',
            'text' => '<span class="text1"><span class="hidden-xs">Register for</span> <b>hundreds of</b></span>
                       <span class="text2"> scholarships</span>
                       <span class="text3"><b>With just one</b> application</span>'
        ], [
            'name' => 'Home page new',
            'text' => '<span class="text1"><span class="hidden-xs">APPLY FOR</span> <b>HUNDREDS</b> OF</span>
                       <span class="text2">SCHOLARSHIPS</span>
                       <span class="text3">WITH JUST <b>ONE REGISTRATION</b></span>'
        ]
        ]);

        \Schema::table('feature_set', function(Blueprint $table) {
            $table->unsignedInteger('homepage_top_block')
                ->after('mobile_payment_set')
                ->default(1);

            $table->foreign('homepage_top_block', 'fk_homepage_top_block_feature_block')
                ->references('id')
                ->on('feature_block');
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
            $table->dropForeign('fk_homepage_top_block_feature_block');
            $table->dropColumn('homepage_top_block');
        });

        \Schema::dropIfExists('feature_block');
    }
}
