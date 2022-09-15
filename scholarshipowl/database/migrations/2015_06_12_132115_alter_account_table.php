<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAccountTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        return \DB::statement("
          ALTER TABLE `account`
          	ADD COLUMN `referral_code` VARCHAR(8) NULL COMMENT 'Account referral code.' AFTER `remember_token`,
        	ADD UNIQUE KEY `uq_account_referral_code` (`referral_code`);
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
          ALTER TABLE `account`
          	DROP COLUMN `referral_code`,
          	DROP INDEX `uq_account_referral_code`;
        ");
	}

}