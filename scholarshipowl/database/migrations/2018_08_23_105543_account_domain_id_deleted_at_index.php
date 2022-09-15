<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountDomainIdDeletedAtIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account', function(Blueprint $table)
        {
            $table->index(['domain_id', 'deleted_at'], 'account_domain_id_deleted_at_index' );
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
            $table->dropIndex('account_domain_id_deleted_at_index');
        });
    }
}
