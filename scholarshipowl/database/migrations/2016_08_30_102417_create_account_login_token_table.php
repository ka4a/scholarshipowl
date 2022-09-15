<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountLoginTokenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_login_token', function (Blueprint $table) {
            $table->integer('account_id')->unsigned();
            $table->string('token', 32)->nullable()->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamps();

            $table->primary("account_id");
            $table->foreign('account_id')->references('account_id')->on('account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('account_login_token');
    }
}
