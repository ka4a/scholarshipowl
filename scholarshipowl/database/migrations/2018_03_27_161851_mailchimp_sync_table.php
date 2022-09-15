<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MailchimpSyncTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mailchimp_sync', function (Blueprint $table) {
            $table->unsignedInteger('id', true);

            $table->unsignedInteger('account_id');

            $table->timestamp('l_general_time')->comment('Time of adding or updating a user in the list')->nullable();
            $table->smallInteger('l_general_status')->nullable();

            $table->timestamp('l_paid_time')->nullable();
            $table->smallInteger('l_paid_status')->nullable();

            $table->timestamp('l_monetization_time')->nullable();
            $table->smallInteger('l_monetization_status')->nullable();

            $table->timestamp('l_conversion_time')->nullable();
            $table->smallInteger('l_conversion_status')->nullable();

            $table->timestamp('l_owl_time')->nullable();
            $table->smallInteger('l_owl_status')->nullable();

            $table->timestamp('l_newsletter_time')->nullable();
            $table->smallInteger('l_newsletter_status')->nullable();

            $table->timestamp('l_special_offers_time')->nullable();
            $table->smallInteger('l_special_offers_status')->nullable();


            $table->unique('account_id');
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
        Schema::drop('mailchimp_sync');
    }
}
