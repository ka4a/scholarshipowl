<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailInbox extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('email')) {
            Schema::table('email', function (Blueprint $table) {
                $table->char('mailbox', 255)->nullable()->after('account_id');
                $table->index(['mailbox']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('email')) {
            Schema::table('email', function (Blueprint $table) {
                $table->dropColumn('mailbox', 255)->nullable()->after('account_id');
                $table->dropIndex(['mailbox']);
            });
        }
    }
}
