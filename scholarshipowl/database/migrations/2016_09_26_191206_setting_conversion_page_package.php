<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SettingConversionPagePackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table("setting")->insert([
            "name" => "payment.conversion.page.package",
            "title" => "Package displayed on conversion landing page.",
            "group" => "Payments",

            "type" => "int",
            "value" => 1,
        ]);

        \DB::table("setting")->insert([
            "name" => "payment.conversion.page.text",
            "title" => "Text displayed on conversion landing page.",
            "group" => "Payments",

            "type" => "text",
            "value" => "Premium Membership for $10.00 ($15) per month upgrade and save $5 every month",
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table('setting')->where('name', 'payment.conversion.page.package')->delete();
        \DB::table('setting')->where('name', 'payment.conversion.page.text')->delete();
    }
}
