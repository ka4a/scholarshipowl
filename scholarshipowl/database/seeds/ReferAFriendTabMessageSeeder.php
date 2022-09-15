<?php

class ReferAFriendTabMessageSeeder extends Seeder {
	public function run() {
		DB::table("setting")->insert(array(
			"name" => "refer_a_friend.tab_above_message",
			"title" => "Refer A Friend Tab Above Message",
			"value" => "",
			"type" => "text",
			"group" => "Refer A Friend"
		));
		
		DB::table("setting")->insert(array(
			"name" => "refer_a_friend.tab_below_message",
			"title" => "Refer A Friend Tab Below Message",
			"value" => "",
			"type" => "text",
			"group" => "Refer A Friend"
		));
	}
}
