<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPackageTable3 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        return \DB::statement("
            ALTER TABLE `package`
            CHANGE COLUMN `expiration_period_value` `expiration_period_value` SMALLINT(4)  NULL DEFAULT '0' COMMENT 'Package expiration period value.';
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
            CHANGE COLUMN `expiration_period_value` `expiration_period_value` TINYINT(3) NULL DEFAULT '0' COMMENT 'Package expiration period value.';
        ");
	}

}
