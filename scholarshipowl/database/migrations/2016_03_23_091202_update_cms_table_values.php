<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCmsTableValues extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			UPDATE `cms` SET `url`='/resources-and-education-center' WHERE `cms_id`='15';
		");

		DB::statement("
			UPDATE `cms` SET `url`='/international-scholarships' WHERE `cms_id`='16';
		");

		DB::statement("
			UPDATE `cms` SET `url`='/what-people-say-about-scholarshipowl' WHERE `cms_id`='18';
		");

		DB::statement("
			UPDATE `cms` SET `url`='/howtoapply' WHERE `cms_id`='29';
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
