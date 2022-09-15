<?php

/**
 * Author: Ivan Krkotic <ivan@siriomedia.com>
 * Date: 21/10/15
 */
class MissionGoalTypeAdvertisement extends \Illuminate\Database\Seeder{
	public function run() {
		DB::table("mission_goal_type")->insert(array(
			"mission_goal_type_id" => 3,
			"name" => "Advertisement",
		));
	}
}