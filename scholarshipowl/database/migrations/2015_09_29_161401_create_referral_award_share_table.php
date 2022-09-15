<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReferralAwardShareTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("CREATE  TABLE `referral_award_share` (
		  `referral_award_id` INT(11) UNSIGNED NOT NULL COMMENT 'Foreign key from referral_award table.' ,
		  `referral_channel` ENUM('Facebook','Twitter','Pinterest','Whatsapp','SMS','Email','Link') NOT NULL DEFAULT \"Link\" COMMENT 'Name of social channel.' ,
		  `share_number` SMALLINT NOT NULL DEFAULT 0 COMMENT 'Number of shares required for the reward.' ,
		  PRIMARY KEY (`referral_award_id`, `referral_channel`) ,
		  INDEX `fk_referral_award_share_referral_award_idx` (`referral_award_id` ASC) ,
		  CONSTRAINT `fk_referral_award_share_referral_award`
			FOREIGN KEY (`referral_award_id` )
			REFERENCES `referral_award` (`referral_award_id` )
			ON DELETE NO ACTION
			ON UPDATE NO ACTION)
		COMMENT = 'Keeps settings for sharing referral award.';");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("DROP TABLE `referral_award_share`;");
	}

}
