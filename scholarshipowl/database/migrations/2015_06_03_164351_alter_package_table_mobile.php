<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AlterPackageTableMobile extends Migration {
	public function up() {
		return \DB::statement("
			ALTER TABLE `package`
			ADD COLUMN `is_mobile_active` tinyint(1) DEFAULT '0' COMMENT 'Is package mobile active.' AFTER `is_automatic`,
			ADD COLUMN `is_mobile_marked` tinyint(1) DEFAULT '0' COMMENT 'Is package mobile marked.' AFTER `is_mobile_active`
		");
	}

	public function down() {
		return \DB::statement("
			ALTER TABLE `package`
			DROP COLUMN `is_mobile_active`,
			DROP COLUMN `is_mobile_marked`
		");
	}
}
