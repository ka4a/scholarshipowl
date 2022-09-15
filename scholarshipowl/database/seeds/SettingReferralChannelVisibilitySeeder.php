<?php

/**
 * Created by Ivan Krkotic <ivan@siriomedia.com>
 * Date: 29/09/15
 * Time: 12:11
 */
class SettingReferralChannelVisibilitySeeder extends Seeder {
	public function run() {
		\DB::table('setting')->insert(array(
			"name" => "referral.channels",
			"title" => "Which Social Channels Are Displayed In Refer A Friend Missions.",
			"value" => "[\"show_all\"]",
			"type" => "array",
			"group" => "Refer A Friend",
			"options" => '{"show_all":"Show All", "show_none":"Show None", "fb":"Facebook", "tw":"Twitter", "pi":"Pinterest", "wa":"WhatsApp", "sm":"SMS", "ma":"Mail"}'
		));
	}
}