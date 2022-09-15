<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAccountAddMailchimpList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("account", function (Blueprint $table) {
            $table->integer("mailchimp_list_id", false, true)->nullable()->default(1);

            $table->foreign("mailchimp_list_id")->references("id")->on("mailchimp_list");
        });

        DB::statement("UPDATE account SET mailchimp_list_id = 2 WHERE domain_id = 2;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("account", function (Blueprint $table) {
            $table->dropForeign(['mailchimp_list_id']);
            $table->dropColumn("mailchimp_list_id");
        });
    }
}
