<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketingDataIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('marketing_system_account_data', function(Blueprint $table) {
            $table->string('name', 32)->change();
            $table->string('value', 255)->change();
        });
        \Schema::table('marketing_system_account_data', function(Blueprint $table) {
            $table->index(['name', 'value'], 'ix_marketing_data_name_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('marketing_system_account_data', function(Blueprint $table) {
            $table->dropIndex('ix_marketing_data_name_value');
        });
        \Schema::table('marketing_system_account_data', function(Blueprint $table) {
            $table->string('name', 255)->change();
            $table->string('value', 1023)->change();
        });
    }
}
