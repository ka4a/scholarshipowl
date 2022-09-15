<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountDomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('name');
        });

        DB::table('domain')->insert([
            [
                'name' => 'scholarshipowl.com',
            ], [
                'name' => 'apply.me',
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('domain');
    }
}
