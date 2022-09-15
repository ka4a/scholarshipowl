<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        return \DB::statement("
            CREATE TABLE `referral` (
            	`referral_account_id` INT(11) UNSIGNED NOT NULL,
            	`referred_account_id` INT(11) UNSIGNED NOT NULL,
            	PRIMARY KEY (`referral_account_id`, `referred_account_id`),
        		KEY `ix_referral_referral_account_id` (`referral_account_id`),
        		KEY `ix_referral_referred_account_id` (`referred_account_id`),
        		CONSTRAINT `fk_referral_referral_account` FOREIGN KEY (`referral_account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        		CONSTRAINT `fk_referral_referred_account` FOREIGN KEY (`referred_account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
        	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds referrals.';
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
          DROP TABLE `referral`;
        ");
    }

}
