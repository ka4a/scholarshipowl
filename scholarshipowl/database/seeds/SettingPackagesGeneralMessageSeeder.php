<?php

class SettingPackagesGeneralMessageSeeder extends Seeder {
	public function run() {
		DB::table('setting')->insert(array(
			"name" => "packages.general_message",
			"title" => "General Packages Message",
			"value" => "",
			"type" => "text",
			"group" => "Packages"
		));
	}
}
