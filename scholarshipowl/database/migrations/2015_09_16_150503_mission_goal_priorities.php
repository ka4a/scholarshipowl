<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MissionGoalPriorities extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return \DB::statement("
            CREATE TABLE `mission_goal_priorities` (
            	`mission_goal_priority_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            	`mission_id` INT(11) UNSIGNED,
            	`mission_goal_properties` VARCHAR(2045),
            	PRIMARY KEY (`mission_goal_priority_id`)
        	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds show priorities for mission goals.';
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return \DB::statement("
          DROP TABLE `mission_goal_priorities`;
        ");
    }

}
