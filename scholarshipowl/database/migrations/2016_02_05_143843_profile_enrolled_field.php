<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProfileEnrolledField extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement("
            ALTER TABLE `profile`
            ADD COLUMN `enrolled` TINYINT(1) NULL DEFAULT NULL AFTER `highschool`;
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement("
            ALTER TABLE `profile`
            DROP COLUMN `enrolled`
        ");
	}

}
