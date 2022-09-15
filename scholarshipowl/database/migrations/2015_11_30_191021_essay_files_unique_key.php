<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EssayFilesUniqueKey extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		return \DB::statement("
          ALTER TABLE `essay_files`
        	ADD UNIQUE KEY `uq_essay_files` (`essay_id`,`scholarship_id`,`file_id`);
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		return \DB::statement("
          ALTER TABLE `essay_files`
          	DROP INDEX `uq_essay_files`;
        ");
	}

}
