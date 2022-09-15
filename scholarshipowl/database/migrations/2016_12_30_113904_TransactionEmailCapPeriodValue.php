<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionEmailCapPeriodValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('transactional_email', function(Blueprint $table) {
            $table->unsignedInteger('cap_value')->default(1)->after('cap_period');
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
            $table->removeColumn('cap_value');
        });
    }
}
