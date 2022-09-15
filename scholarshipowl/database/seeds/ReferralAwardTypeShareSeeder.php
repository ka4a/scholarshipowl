<?php

/**
 * Author: Ivan Krkotic <ivan@siriomedia.com>
 * Date: 29/09/15
 */
class ReferralAwardTypeShareSeeder extends Seeder{
	public function run() {
		DB::table("referral_award_type")->insert(array(
			"name" => "Number Of Shares"
		));
	}
}