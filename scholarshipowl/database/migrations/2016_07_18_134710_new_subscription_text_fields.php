<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewSubscriptionTextFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('setting') ->insert([
            'setting_id' => 36,
            'name' => 'scholarships.active_text',
            'title' => 'Scholarship active subscription text',
            'value' => '"You can cancel your membership any time. Please call us toll free on +1 800 494 49 08 for immediately speaking to one of our agents. Or click <a id=\"cancel-membership\" href=\"[[cancelUrl]]\">here</a> to request a membership cancellation which will be processed within five business days."',
            'type' => 'text',
            'group' => 'Scholarships'
        ]);

        \DB::table('setting') ->insert([
            'setting_id' => 37,
            'name' => 'scholarships.cancell_text',
            'title' => 'Scholarship cancell subscription text',
            'value' => '"Thank you. Your cancellation request will be processed within five business days. Your membership will remain active until [[expirationDate]]"',
            'type' => 'text',
            'group' => 'Scholarships'
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table("setting")->where(["name" => "scholarships.active_text"])->delete();
        \DB::table("setting")->where(["name" => "scholarships.cancell_text"])->delete();
    }
}
