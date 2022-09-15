<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPremiumInboxSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		\DB::statement("INSERT INTO `setting` (`name`, `title`, `value`, `options`, `type`, `group`)
				VALUES ('premiuminbox.enabled', 'Enabled', '\"yes\"', '{\"yes\":\"Yes\",\"no\":\"No\"}',
				'select', 'Premium Inbox');
		");

		\DB::statement("INSERT INTO `setting` (`name`, `title`, `value`, `type`, `group`)
				VALUES ('premiuminbox.text', 'Button Text', '\"Click here to upgrade and access your mailbox\"',
				'string', 'Premium Inbox');
        ");

		\DB::statement("INSERT INTO `setting` (`name`, `title`, `value`, `type`, `group`)
				VALUES ('premiuminbox.text_mobile', 'Mobile Button Text', '\"Click here to upgrade <br />and access your mailbox\"',
				'string', 'Premium Inbox');
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		\DB::statement("DELETE FROM `setting` WHERE `name`='premiuminbox.enabled';");
		\DB::statement("DELETE FROM `setting` WHERE `name`='premiuminbox.text';");
		\DB::statement("DELETE FROM `setting` WHERE `name`='premiuminbox.text_mobile';");
	}

}
