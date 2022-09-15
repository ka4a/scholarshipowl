<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveExpireDateFromRecurrentSubscriptions extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \DB::statement("
            UPDATE subscription,
                package
            SET
                subscription.end_date = '0000-00-00 00:00:00'
            WHERE
                subscription.package_id = package.package_id
                AND package.expiration_type = 'recurrent';
        ");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
