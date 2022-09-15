<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PackageStylesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

        Schema::create('package_style', function(Blueprint $table) {
            $table->integer('package_id', false, true);
            $table->foreign('package_id')->references('package_id')->on('package');

            $table->string('element');
            $table->text('content');
            $table->text('css');

            $table->primary(array('package_id', 'element'));
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
        Schema::dropIfExists('package_style');
	}

}
