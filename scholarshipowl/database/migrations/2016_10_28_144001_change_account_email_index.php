<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAccountEmailIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account', function(Blueprint $table) {
            $table->dropUnique('uq_account_email');
            $table->unique(['email', 'domain_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account', function(Blueprint $table) {
            $table->dropUnique(['email', 'domain_id']);
            $table->unique('email', 'uq_account_email');
        });
    }
}
