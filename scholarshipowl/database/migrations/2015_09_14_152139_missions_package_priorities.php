<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MissionsPackagePriorities extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        return \DB::statement("
            CREATE TABLE `package_priorities` (
            	`package_priorities_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            	`package_priorities_parent_id` INT(11) UNSIGNED,
            	`type` ENUM('package','mission'),
            	`item_id` INT(11) UNSIGNED NOT NULL,
            	PRIMARY KEY (`package_priorities_id`)
        	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds show priorities for missions and packages.';
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
          DROP TABLE `package_priorities`;
        ");
    }

}
