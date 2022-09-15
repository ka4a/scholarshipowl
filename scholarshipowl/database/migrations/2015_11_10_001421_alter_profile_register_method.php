<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProfileRegisterMethod extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		return \DB::statement("
            ALTER TABLE `profile`
            ADD COLUMN `distribution_channel` enum('web_app','ios','android') NOT NULL DEFAULT 'web_app' COMMENT 'Profile distributon channel.' ,
            ADD COLUMN `signup_method` enum('fb_connect','google+','manual') NOT NULL DEFAULT 'manual' COMMENT 'Signup method'
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
            ALTER TABLE `profile`
            DROP COLUMN `distribution_channel`,
            DROP COLUMN `signup_method`
        ");
	}


}
