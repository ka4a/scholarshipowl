<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableScholarshipFileAssociation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement(
			"ALTER TABLE `scholarship`
				add column files_alowed boolean
				"
		);

//		DB::statement(
//			"UPDATE `scholarship` set files_alowed = true where application_type = 'email'"
//		);

//		DB::statement(
//			"UPDATE `scholarship` set files_alowed = false where application_type <> 'email'"
//		);

		DB::statement(
			"UPDATE `scholarship` set files_alowed = false"
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
			"ALTER TABLE `scholarship` drop column files_alowed"
		);
	}


}
