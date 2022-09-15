<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogGtsFormUrlTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('log_gts_form_url', function(Blueprint $table)
		{
            $table->increments('log_gts_form_url_id');
            $table->integer('account_id', false, true);
            $table->text('form_url');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::dropIfExists('log_gts_form_url');
	}

}
