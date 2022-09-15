<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FailedTriesFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_failed_tries', function (Blueprint $table) {
            $table->unsignedInteger('account_id')->change();
            $table->unsignedInteger('scholarship_id')->change();

            $table->foreign('account_id')
                ->references('account_id')
                ->on('account');

            $table->foreign('scholarship_id')
                ->references('scholarship_id')
                ->on('scholarship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_failed_tries', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropForeign(['scholarship_id']);
            $table->integer('account_id')->change();
            $table->integer('scholarship_id')->change();
        });
    }
}
