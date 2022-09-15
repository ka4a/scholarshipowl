<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionOneToManyTransaction extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        DB::statement("
            ALTER TABLE `transaction`
              ADD COLUMN `subscription_id` INT(11) UNSIGNED NOT NULL AFTER `transaction_id`,
              ADD INDEX `ix_transaction_subscription_id` (`subscription_id` ASC);
        ");

        DB::statement("SET FOREIGN_KEY_CHECKS = 0;");
        DB::statement("
            ALTER TABLE `transaction`
              ADD CONSTRAINT `fk_transaction_subscription` FOREIGN KEY (`subscription_id`)
              REFERENCES `subscription` (`subscription_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
        ");
        DB::statement("SET FOREIGN_KEY_CHECKS = 1;");

        DB::statement("
            INSERT INTO `transaction` (transaction_id, subscription_id)
            SELECT s.transaction_id, s.subscription_id FROM subscription s JOIN transaction t ON t.transaction_id = s.transaction_id
            ON DUPLICATE KEY UPDATE subscription_id = VALUES(subscription_id);
		");

        DB::statement("
            ALTER TABLE `subscription`
              DROP FOREIGN KEY fk_subscription_transaction,
              DROP INDEX ix_subscription_transaction_id,
              DROP COLUMN `transaction_id`;
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        DB::statement("
            ALTER TABLE `subscription`
              ADD COLUMN `transaction_id` int(11) unsigned DEFAULT NULL COMMENT 'Transaction id. Foreign key to transaction table.' AFTER `end_date`,
              ADD INDEX `ix_subscription_transaction_id` (`transaction_id`),
              ADD CONSTRAINT `fk_subscription_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transaction` (`transaction_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
        ");

        DB::statement("
            INSERT INTO `subscription` (subscription_id, transaction_id)
            SELECT t.subscription_id, t.transaction_id FROM transaction t JOIN subscription s ON s.subscription_id = t.subscription_id
            ON DUPLICATE KEY UPDATE transaction_id = VALUES(transaction_id);
		");

        DB::statement("
            ALTER TABLE `transaction`
              DROP FOREIGN KEY fk_transaction_subscription,
              DROP INDEX ix_transaction_subscription_id,
              DROP COLUMN `subscription_id`;
        ");

	}

}
