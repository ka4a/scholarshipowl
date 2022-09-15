<?php

class SettingPackagesGeneralMobileMessageSeeder extends Seeder {
	public function run() {
		DB::table('setting')->insert(array(
			"name" => "packages.general_mobile_message",
			"title" => "General Packages Mobile Message",
			"value" => "",
			"type" => "text",
			"group" => "Packages"
		));
	}
}
