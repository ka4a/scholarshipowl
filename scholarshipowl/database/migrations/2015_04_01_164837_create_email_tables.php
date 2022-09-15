<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTables extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `email` (
			  `email_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `account_id` int(11) unsigned NOT NULL COMMENT 'Account id. Foreign key to account table.',
			  `scholarship_id` int(11) unsigned COMMENT 'Scholarship id. Foreign key to scholarship table',
			  `subject` varchar(2045) NOT NULL COMMENT 'Email subject.',
			  `body` varchar(65000) NOT NULL COMMENT 'Email body.',
			  `sender` varchar(1023) NOT NULL COMMENT 'Email sender. From address.',
			  `recipient` varchar(1023) NOT NULL COMMENT 'Email recipient. To address.',
			  `date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Email date.',
			  `folder` enum('inbox','sent','draft','trash','owl','other','undelivered') NOT NULL DEFAULT 'inbox' COMMENT 'Email folder.',
			  `size` varchar(127) NOT NULL COMMENT 'Email size in bytes.',
			  `is_read` tinyint(1) unsigned DEFAULT '0' COMMENT 'Is email read.',
			  `is_flagged` tinyint(1) unsigned DEFAULT '0' COMMENT 'Is email flagged.',
			  `is_answered` tinyint(1) unsigned DEFAULT '0' COMMENT 'Is email answered.',
			  `message_id` varchar(1023) COMMENT 'Original email message id.',
			  `message_mailbox_id` varchar(1023) COMMENT 'Original email mailbox message id.',
			  `in_reply_to` varchar(1023) COMMENT 'Hash referencing if mail is reply, reference is message_id.',
			  `raw_overview` varchar(65000) COMMENT 'Email raw header overview. Json serialized.',
			  `raw_structure` varchar(65000) COMMENT 'Email raw structure parts overview. Json serialized.',
			  PRIMARY KEY (`email_id`),
			  KEY `ix_email_account_id` (`account_id`),
			  KEY `ix_email_scholarship_id` (`scholarship_id`),
			  KEY `ix_email_date` (`date`),
			  KEY `ix_email_is_read` (`is_read`),
			  CONSTRAINT `fk_email_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
			  CONSTRAINT `fk_email_scholarship` FOREIGN KEY (`scholarship_id`) REFERENCES `scholarship` (`scholarship_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds emails.';
		");
		
		\DB::statement("
			CREATE TABLE `email_attachment` (
			  `email_attachment_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			  `email_id` int(11) unsigned NOT NULL COMMENT 'Email id. Foreign key to email table.',
			  `name` varchar(2045) NOT NULL COMMENT 'Attachment name.',
			  `filename` varchar(2045) NOT NULL COMMENT 'Attachment filename.',
			  PRIMARY KEY (`email_attachment_id`),
			  KEY `ix_email_attachment_email_id` (`email_id`),
			  CONSTRAINT `fk_email_attachment_email` FOREIGN KEY (`email_id`) REFERENCES `email` (`email_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds email attachments.';
		");
	}

	public function down() {
		\DB::statement("DROP TABLE `email_attachment`;");
		\DB::statement("DROP TABLE `email`;");
	}
}
