<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountFileChangePrimaryKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_file', function (Blueprint $table) {
            $table->dropPrimary('path');
        });
        Schema::table('account_file', function (Blueprint $table) {
            $table->increments('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_file', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('account_file', function (Blueprint $table) {
            $table->primary('path');
        });
    }
}
