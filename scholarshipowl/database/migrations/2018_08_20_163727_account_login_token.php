<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountLoginToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('account_login_token', function (Blueprint $table) {
            $table->dropColumn('updated_at');
            $table->dropForeign(['account_id']);
            $table->dropPrimary(['account_id']);
        });

        \Schema::table('account_login_token', function (Blueprint $table) {
            $table->unsignedInteger('id', true);
            $table->foreign('account_id')->references('account_id')->on('account');
            $table->string('token', 32)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('account_login_token', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable();
            $table->dropColumn('id');
            $table->dropForeign(['account_id']);
        });
    }
}
