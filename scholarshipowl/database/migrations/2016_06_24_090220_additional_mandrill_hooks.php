<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditionalMandrillHooks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("transactional_email")->insert([
            "event_name" => "Select Password",
            "subject" => "Password Selected",
            "template_name" => "select-password",
            "from_name" => "ScholarshipOwl Site Account",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table("transactional_email")->where(["template_name" => "select-password"])->delete();
    }
}
