<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateSubmissionTable extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `submission` (
			`submission_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`account_id` int(11) unsigned NOT NULL COMMENT 'Account id. Foreign key to account table.',
			`ip_address` varchar(2045) NULL COMMENT 'Account ip address.',
			`name` varchar(127) NOT NULL COMMENT 'Submissions name.',
			`status` enum('pending','success','error') NOT NULL DEFAULT 'pending' COMMENT 'Submissions status.',
			`response` varchar(2045) NOT NULL COMMENT 'Submissions response.',
			`send_date` timestamp NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date when submission is sent.',
			PRIMARY KEY(`submission_id`),
			KEY `ix_submission_account_id` (`account_id`),
			KEY `ix_submission_name` (`name`),
			KEY `ix_submission_send_date` (`send_date`),
			CONSTRAINT `fk_submission_submission` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds submissions.'
		;");
	}

	public function down() {
		\DB::statement("DROP TABLE `submission`");
	}
}
