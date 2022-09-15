<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateForgotPasswordTable extends Migration {
	public function up() {
		return \DB::statement("CREATE TABLE `forgot_password` (
		  `account_id` int(11) unsigned NOT NULL COMMENT 'Primary key. Foreign key to account table.',
		  `token` varchar(100) NOT NULL COMMENT 'Token sent to account email.',
		  `expire_date` datetime NOT NULL COMMENT 'Date when token expires.',
		  PRIMARY KEY (`account_id`),
		  UNIQUE KEY `ix_forgot_password_token` (`token`),
		  KEY `ix_forgot_password_account_id` (`account_id`),
		  CONSTRAINT `fk_forgot_password_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds forgot password tokens.';
		");
	}

	public function down() {
		return \DB::statement("DROP TABLE `forgot_password`");
	}
}
