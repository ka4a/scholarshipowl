<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterScholarshipTableAddIsAutomatic extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			ALTER TABLE `scholarship`
			ADD COLUMN `is_automatic` TINYINT(1) NULL DEFAULT '0' COMMENT 'Is the scholarship issued automatically on registration' AFTER `files_alowed`;
		");

		//	Automatically assign currently active YDIT scholarship
		DB::table("scholarship")
			->where("scholarship_id", 1215)
			->update(array("is_automatic" => 1));
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("
			ALTER TABLE scholarship`
			DROP COLUMN `is_automatic`;
		");
	}

}
