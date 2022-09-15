<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateSubscriptionAcquiredTypeTable extends Migration {
	public function up() {
		\DB::statement("
			CREATE TABLE `subscription_acquired_type` (
			`subscription_acquired_type_id` tinyint(1) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary key.',
			`name` varchar(63) NOT NULL COMMENT 'Subscription acquired type name.',
			PRIMARY KEY(`subscription_acquired_type_id`)			
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table holds subscription acquired types.'
		;");
		
		\DB::statement("
			INSERT INTO `subscription_acquired_type` VALUES
			(1, 'Purchased'),
			(2, 'Welcome'),
			(3, 'Referral'),
			(4, 'Referred'),
			(5, 'Mission'),
			(6, 'Freebie')
		;");
		
		\DB::statement("
			ALTER TABLE `subscription`
			ADD COLUMN `subscription_acquired_type_id` tinyint(1) unsigned NULL COMMENT 'Subscription acquired type. Foreign key to subscription_acquired_type table.' AFTER `subscription_status_id`,
			ADD KEY `ix_subscription_subscription_acquired_type_id` (`subscription_acquired_type_id`),
			ADD CONSTRAINT `fk_subscription_subscription_acquired_type` FOREIGN KEY (`subscription_acquired_type_id`) REFERENCES `subscription_acquired_type` (`subscription_acquired_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
		;");
		
		\DB::statement("UPDATE `subscription` SET subscription_acquired_type_id = 1;");
	}

	public function down() {
		\DB::statement("
			ALTER TABLE `subscription`
			DROP COLUMN `subscription_acquired_type_id`,
			DROP KEY `ix_subscription_subscription_acquired_type_id`,
			DROP CONSTRAINT `fk_subscription_subscription_acquired_type`
		;");
		
		\DB::statement("DROP TABLE `subscription_acquired_type`;");
	}
}
