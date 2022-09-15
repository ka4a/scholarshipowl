<?php

class SettingMissionsGeneralMessageSeeder extends Seeder {
	public function run() {
		DB::table('setting')->insert(array(
			"name" => "missions.general_message",
			"title" => "General Missions Message",
			"value" => "",
			"type" => "text",
			"group" => "Missions"
		));
	}
}
