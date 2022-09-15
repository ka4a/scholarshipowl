<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCollegeExpressCoreg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table("coreg_plugins")->insert(array(
            "name" => "CollegeExpress",
            "is_visible" => 0,
            "monthly_cap" => null,
            "text" => "Yes, I am interested in learning more about CollegeXpress' $10,000 Scholarship. <a href=\"http://www.collegexpress.com/carnegie_scholarship/?utm_source=ScholarshipOwl&utm_medium=offer&utm_campaign=SO_10753&utm_content=10k\" target=\"_blank\">Terms and Conditions</a>. <a href=\"http://www.collegexpress.com/privacy/?utm_source=ScholarshipOwl&utm_medium=offer&utm_campaign=SO_10753&utm_content=privacy\" target=\"_blank\">Privacy Policy</a>.",
            "display_position" => "coreg6a"
        ));

        DB::table("redirect_rules_set")->insert(array(
            "name" => "CollegeExpress",
            "type" => "AND",
            "table_name" => "profile",
        ));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::delete("DELETE FROM coreg_plugins WHERE name = 'CollegeExpress';");

        DB::delete("DELETE FROM redirect_rules_set WHERE name = 'CollegeExpress';");
    }
}
