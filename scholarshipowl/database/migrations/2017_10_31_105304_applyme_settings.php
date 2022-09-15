<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplymeSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applyme_settings', function($table) {
			$table->increments('id');
			$table->string('name', 50);
			$table->string('title', 100);
			$table->string('value', 255);
		});

		\DB::table('applyme_settings')->insert([
			'name' 	=> 'swipes_per_day',
			'value' => '50',
			'title' => 'Swipes Per Day'
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applyme_settings');
    }
}
