<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMailchimpSkip extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mailchimp_skip', function(Blueprint $table) {
			$table->increments('mailchimp_skip_id');
			$table->text('email');
			$table->integer('account_id')->unsigned()->unique();
			$table->foreign('account_id')->references('account_id')->on('account');
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
		Schema::dropIfExists('mailchimp_skip');
	}

}
