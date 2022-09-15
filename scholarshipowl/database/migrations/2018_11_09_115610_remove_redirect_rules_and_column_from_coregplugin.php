<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRedirectRulesAndColumnFromCoregplugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Schema::table('coreg_plugins', function(Blueprint $table) {
            $table->dropForeign('fk_coreg_plugins_rediredt_rules_set');
            $table->dropColumn('redirect_rules_set_id');
        });

        DB::table("redirect_rule")->where("redirect_rules_set_id", 3)->delete(); //Berecruited
        DB::table("redirect_rule")->where("redirect_rules_set_id", 4)->delete(); //Cappex
        DB::table("redirect_rule")->where("redirect_rules_set_id", 5)->delete(); //Simple Tuition
        DB::table("redirect_rule")->where("redirect_rules_set_id", 6)->delete(); //WayUp
        DB::table("redirect_rule")->where("redirect_rules_set_id", 8)->delete(); //DoublePositive
        DB::table("redirect_rule")->where("redirect_rules_set_id", 9)->delete(); //ChristianConnector
        DB::table("redirect_rule")->where("redirect_rules_set_id", 10)->delete(); //CollegeExpress
        DB::table("redirect_rule")->where("redirect_rules_set_id", 16)->delete(); //Ziprecruiter
        DB::table("redirect_rule")->where("redirect_rules_set_id", 21)->delete(); //BirdDog AA
        DB::table("redirect_rule")->where("redirect_rules_set_id", 22)->delete(); //BirdDog Asian
        DB::table("redirect_rule")->where("redirect_rules_set_id", 23)->delete(); //BirdDog Hispanic
        DB::table("redirect_rule")->where("redirect_rules_set_id", 24)->delete(); //BirdDog Female

        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 3)->delete(); //Berecruited
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 4)->delete(); //Cappex
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 5)->delete(); //Simple Tuition
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 6)->delete(); //WayUp
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 8)->delete(); //DoublePositive
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 9)->delete(); //ChristianConnector
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 10)->delete(); //CollegeExpress
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 16)->delete(); //Ziprecruiter

        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 21)->delete(); //BirdDog AA
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 22)->delete(); //BirdDog Asian
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 23)->delete(); //BirdDog Hispanic
        DB::table("redirect_rules_set")->where("redirect_rules_set_id", 24)->delete(); //BirdDog Female
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
