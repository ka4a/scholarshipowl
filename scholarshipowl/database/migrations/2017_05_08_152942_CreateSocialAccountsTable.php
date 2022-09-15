<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_account', function (Blueprint $table) {
            $table->integer('account_id', false, true);
            $table->string('provider_user_id');
            $table->string('provider');
            $table->string('token')->nullable();

            $table->primary("account_id");
            $table->foreign("account_id")->references("account_id")->on("account");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('social_account');
    }
}
