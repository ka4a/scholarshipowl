<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageGate2shopRebillingPlan extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('package', function(Blueprint $table) {
            $table->string('g2s_product_id')->nullable();
            $table->string('g2s_template_id')->nullable();
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        \DB::statement("ALTER TABLE `package` DROP COLUMN `g2s_product_id`, DROP COLUMN `g2s_template_id`;");
	}

}
