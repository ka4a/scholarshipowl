<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterZuUsaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("zu_usa_campaign", function (Blueprint $table){
            $table->string("submission_value")->nullable();
        });

        Schema::table("zu_usa_campus", function (Blueprint $table){
            $table->integer("monthly_cap", false, true)->nullable();
        });

        Schema::dropIfExists("zu_usa_campus_allocation");
        Schema::create("zu_usa_campus_allocation", function(Blueprint $table){
            $table->integer("zu_usa_campus", false, true);
            $table->date("date");
            $table->string("type", 5);
            $table->integer("count", false, true)->default(1);

            $table->primary(["zu_usa_campus", "date", "type"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("zu_usa_campaign", function (Blueprint $table){
            $table->dropColumn("submission_value");
        });

        Schema::table("zu_usa_campus", function (Blueprint $table){
            $table->dropColumn("monthly_cap");
        });

        Schema::dropIfExists("zu_usa_campus_allocation");
    }
}
