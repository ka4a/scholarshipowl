<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('account', function(Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->index('deleted_at');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Schema::table('account', function(Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropIndex('deleted_at');
        });
    }
}
