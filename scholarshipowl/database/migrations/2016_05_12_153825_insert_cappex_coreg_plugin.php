<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCappexCoregPlugin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("
			INSERT INTO `coreg_plugins` (`name`, `is_visible`, `text`, `monthly_cap`, `display_position`) VALUES ('Cappex', '1', 'Enter for a chance to win a $1,000 monthly scholarship from Cappex!', 5000, 'coreg5a');
		");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::delete("DELETE FROM coreg_plugins WHERE name = 'Cappex';");
	}

}
