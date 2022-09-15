<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountOnboardingCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_onboarding_call', function (Blueprint $table) {
            $table->integer('account_id')->unsigned()->unique();
            $table->boolean('call1');
            $table->boolean('call2');
            $table->boolean('call3');
            $table->boolean('call4');
            $table->boolean('call5');
            $table->timestamps();

            $table->primary('account_id');
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
        Schema::drop('account_onboarding_call');
    }
}
