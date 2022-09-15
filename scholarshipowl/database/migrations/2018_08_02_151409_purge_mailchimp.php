<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PurgeMailchimp extends Migration
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
            $table->dropForeign('account_mailchimp_list_id_foreign');
            $table->dropIndex('account_mailchimp_list_id_foreign');
        });

        Schema::table('account', function (Blueprint $table) {
            $table->dropColumn('mailchimp_list_id');
        });

        Schema::drop('mailchimp_list');
        Schema::drop('mailchimp_skip');
        Schema::drop('mailchimp_sync');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
