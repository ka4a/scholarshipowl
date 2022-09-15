<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFilesMime extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(
			"ALTER TABLE `files`
				add column mime_type varchar(255) NOT NULL,
				add column extension varchar(255) NOT NULL
				"
		);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement(
			"ALTER TABLE `files` drop column mime_type, drop column extension"
		);
	}

}
