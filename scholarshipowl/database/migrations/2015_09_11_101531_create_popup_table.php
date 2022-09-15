<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement(
            "CREATE TABLE `popup` (
                `popup_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key for the table.',
                `popup_display` ENUM('0', '1', '2', '3') NOT NULL DEFAULT '0' COMMENT 'Is popup active.\n0 - No\n1 - Before payment\n2 - After payment\n3 - Both',
                `popup_title` VARCHAR(255) NULL COMMENT 'Title displayed in popup.',
                `popup_text` TEXT NULL COMMENT 'Text displayed in popup.',
                `popup_type` ENUM('raf', 'mission', 'package') NULL DEFAULT 'raf' COMMENT 'Type of mission/package to be shown in popup.',
                `popup_target_id` INT(11) UNSIGNED NULL COMMENT 'ID of the mission/package to be shown in popup.',
                `popup_cms_ids` varchar(255) DEFAULT NULL COMMENT 'IDs of CMS pages on which popup is to be shown',
                `start_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Popup configuration start date.',
                `end_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Popup configuration end date.',
                PRIMARY KEY (`popup_id`))
                COMMENT = 'Table holds popup configuration.';"
        );
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::statement(
            "DROP TABLE `popup`;"
        );
	}

}