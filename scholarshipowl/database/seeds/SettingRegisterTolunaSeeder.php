<?php

class SettingRegisterTolunaSeeder extends Seeder {
	public function run() {
		DB::table('setting')->insert(array(
			"name" => "register.toluna",
			"title" => "Toluna Visible",
			"value" => "\"no\"",
			"type" => "select",
			"group" => "Register",
			"options" => '{"yes":"Yes","no":"No"}',
		));

		DB::table('setting')->insert(array(
			"name" => "register.toluna_text",
			"title" => "Toluna Text",
			"value" => "\"Send me an email survey to enter Toluna's $4,500 cash draw!\"",
			"type" => "string",
			"group" => "Register",
			"options" => "",
		));
	}
}
