<?php

class DevAdminSeeder extends Seeder {
	public function run() {
		try {
			$now = date("Y-m-d H:i:s");
			
			
			\DB::beginTransaction();
			
			
			// Account Table
			\DB::table("account")->insert(array(
				"account_status_id" => 3, // Active
				"account_type_id" => 1, // Type
				"email" => "devadmin@scholarshipowl.com",
				"username" => "devadmin",
				"password" => \Hash::make("devadmin"),
				"remember_token" => "",
				"referral_code" => "",
				"created_date" => $now,
				"last_updated_date" => $now,
			));
			
			
			// Profile Table
			$accountId = \DB::getPdo()->lastInsertId();
			\DB::table("profile")->insert(array(
				"account_id" => $accountId,
				"first_name" => "Dev",
				"last_name" => "Admin",
			));
			
			
			\DB::commit();
		}
		catch (\Exception $exc) {
			\DB::rollback();
		}
	}
}
