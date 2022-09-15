<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountLastLoginField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account', function (Blueprint $table) {
            $table->timestamp('last_action_at')->useCurrent();
            $table->index('last_action_at', 'ix_last_action_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account', function (Blueprint $table) {
            $table->dropIndex('ix_last_action_at');
        });
        Schema::table('account', function (Blueprint $table) {
            $table->dropColumn('last_action_at');
        });
    }
}
