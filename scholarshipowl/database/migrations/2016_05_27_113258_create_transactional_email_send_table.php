<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionalEmailSendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("transactional_email_send", function (Blueprint $table){
            $table->increments("transactional_email_send_id")->comment("Primary key");
            $table->integer("transactional_email_id", false, true)->comment("Foreign key from transactional email table");
            $table->integer("account_id", false, true)->comment("Foreign key from account table");
            $table->timestamp("send_date")->comment("Date of sending");

            $table->foreign("transactional_email_id")->references("transactional_email_id")->on("transactional_email");
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
        Schema::drop("transactional_email_send");
    }
}
