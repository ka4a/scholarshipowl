<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionalEmailsDelay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('transactional_email', function(Blueprint $table) {
            $table->string('delay_type', 6)->nullable();
            $table->integer('delay_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('transactional_email', function(Blueprint $table) {
            $table->dropColumn('delay_value');
            $table->dropColumn('delay_type');
        });
    }
}
