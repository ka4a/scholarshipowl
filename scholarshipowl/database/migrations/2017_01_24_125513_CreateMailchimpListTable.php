<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailchimpListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("mailchimp_list", function (Blueprint $table) {
            $table->increments("id");
            $table->string("name");
            $table->string("list_id");
            $table->integer("domain_id", false, true)->nullable()->default(null);

            $table->foreign("domain_id")->references("id")->on("domain");
        });

        DB::table("mailchimp_list")->insert(["name" => "MainList", "list_id" => env("main_list_id", "026356e78d"), "domain_id" => 1]);
        DB::table("mailchimp_list")->insert(["name" => "Apply.Me MasterList", "list_id" => env("apply_me_list_id", "ed20e2ca72"), "domain_id" => 2]);
        DB::table("mailchimp_list")->insert(["name" => "Celebrity Scholarship", "list_id" => env("celebrity_list_id", "84ed350999")]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("mailchimp_list");
    }
}
