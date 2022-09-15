<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AccountDomainField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account', function (Blueprint $table) {
            $table->integer('domain_id')
                ->unsigned()
                ->after('zendesk_user_id')
                ->default(1);
            $table->foreign('domain_id')->references('id')->on('domain');
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
            $table->dropForeign('account_domain_id_foreign');
            $table->dropColumn('domain_id');
        });
    }
}
