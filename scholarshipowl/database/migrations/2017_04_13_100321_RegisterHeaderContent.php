<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RegisterHeaderContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::table('feature_content_set')->update(['register_header' =>
        '<h2 class="text-large text-light" id="select-scholarships-title">Select scholarships</h2>
        <p class="max-bold">We found [[eligible_scholarships_count]] additional scholarships worth $[[eligible_scholarships_amount]] matching your profile. <span class="hidden-xs">ScholarshipOwl matches you with scholarships and makes applying easy and fun.</span></p>
        <p class="max">Our unique scholarship <strong>management tool</strong> saves you the effort of finding and applying to scholarships one at a time! Try the 7 day Free Trial</p>'
        ]);
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
