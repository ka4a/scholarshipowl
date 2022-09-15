<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSubscriptionTable2 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
    public function up()
    {
        return \DB::statement("
            ALTER TABLE `subscription`
            ADD COLUMN `expiration_type` enum('no_expiry','date','period','recurrent') NOT NULL DEFAULT 'date' COMMENT 'Package expiration type.' AFTER `renewal_date`,
            ADD COLUMN `expiration_period_type` enum('day','week','month','year') DEFAULT 'month' COMMENT 'Package expiration period type.' AFTER `expiration_type`,
            ADD COLUMN `expiration_period_value` SMALLINT (4) DEFAULT '0' COMMENT 'Package expiration period value.' AFTER `expiration_period_type`,
            ADD COLUMN `priority` tinyint(3) unsigned DEFAULT '1' AFTER `expiration_period_value`;
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
            ALTER TABLE `subscription`
            DROP COLUMN `expiration_type`,
            DROP COLUMN `expiration_period_type`,
            DROP COLUMN `expiration_period_value`,
            DROP COLUMN `priority`;
        ");
    }

}
