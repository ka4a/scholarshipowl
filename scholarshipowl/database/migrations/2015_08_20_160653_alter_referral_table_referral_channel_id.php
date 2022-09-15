<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReferralTableReferralChannelId extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement(
		    "ALTER TABLE `referral`
              ADD COLUMN `referral_channel` ENUM('Facebook', 'Twitter', 'Pinterest', 'Whatsapp', 'SMS', 'Email', 'Link') NOT NULL DEFAULT 'Link' AFTER `referred_account_id`;"
        );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement(
            "ALTER TABLE `referral`
            DROP COLUMN `referral_channel`;"
        );
	}

}
