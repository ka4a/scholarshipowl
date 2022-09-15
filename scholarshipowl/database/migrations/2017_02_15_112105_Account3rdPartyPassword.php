<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Account3rdPartyPassword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('account', function(Blueprint $table) {
            $table->string('password_external', 32)->after('password');
        });

        \DB::statement('UPDATE account SET password_external = MD5(CONCAT(MD5(NOW()), username));');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('account', function(Blueprint $table) {
            $table->dropColumn('password_external');
        });
    }
}
