<?php

class SettingRemoveInboxDollarsSeeder extends Seeder {
	public function run() {
		DB::delete("
			DELETE FROM `scholarship_owl_dev`.`setting`
			WHERE
				name = 'register.inboxdollars'
				OR name = 'register.inboxdollars_text';
		");
	}
}
