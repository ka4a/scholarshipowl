<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionalEmailFromAndName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('transactional_email', function(Blueprint $table) {
            $table->string('from')->after('subject')->nullable();
            $table->string('sender_address')->after('from')->nullable();
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
            $table->dropColumn('sender_address');
            $table->dropColumn('from');
        });
    }
}
