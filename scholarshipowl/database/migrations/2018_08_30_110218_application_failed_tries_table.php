<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ApplicationFailedTriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::create('application_failed_tries', function (Blueprint $table) {
            $table->integer('scholarship_id');
            $table->integer('account_id');
            $table->integer('tries');
            $table->dateTime('last_update');
            $table->primary(['scholarship_id', 'account_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::dropIfExists('application_failed_tries');
    }
}
