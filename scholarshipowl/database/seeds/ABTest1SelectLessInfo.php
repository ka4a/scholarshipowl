<?php

class ABTest1SelectLessInfo extends Seeder {
	public function run() {
		$description = "
			For Newly Registered Users.\nA. Same as now\nB. For new (signup date => test start date) customers that have not paid (no membership OR membership at $0 cost) hide essay column and disable expanding scholarship info
		";
		
		DB::table("ab_test")->insert(array(
			"ab_test_id" => 1,
			"name" => "Select - Less Info",
			"description" => trim($description),
			"start_date" => date("Y-m-d"),
			"end_date" => date("Y-m-d"),
			"is_active" => 0
		));
	}
}
