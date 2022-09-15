<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        return \DB::statement("
			ALTER TABLE `package`
            CHANGE COLUMN `expiration_type` `expiration_type` ENUM('no_expiry','date','period','recurrent') NOT NULL DEFAULT 'date' COMMENT 'Package expiration type.' ,
            CHANGE COLUMN `expiration_period_type` `expiration_period_type` ENUM('day','week','month','year') NULL DEFAULT 'month' COMMENT 'Package expiration period type.' ,
            ADD COLUMN `is_automatic` TINYINT(1) NULL DEFAULT '0' AFTER `is_marked`,
            ADD COLUMN `priority` TINYINT(3) UNSIGNED NULL DEFAULT '1' AFTER `is_automatic`;
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
			ALTER TABLE `package`
            CHANGE COLUMN `expiration_type` `expiration_type` ENUM('no_expiry','date','period') NOT NULL DEFAULT 'date' COMMENT 'Package expiration type.' ,
            CHANGE COLUMN `expiration_period_type` `expiration_period_type` ENUM('week','month','year') NULL DEFAULT 'month' COMMENT 'Package expiration period type.' ,
            DROP COLUMN `is_automatic`,
            DROP COLUMN `priority`;
		");
	}

}
