<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddISayCoregPlugin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $displayPosition = "coreg6a";
        $linkText = "Join i-Say and earn rewards by sharing your opinion. <a target='_blank' href='https://www.inboxdollars.com/pages/privacy'>Terms of Use</a>
            and <a target='_blank' href='https://i-say.com/Footerlinks/PrivacyPolicy/tabid/282/Default.aspx'>Privacy Policy</a>  
                     
                   .";
        \DB::table("coreg_plugins")->insert([
            "name" => "ISay",
            "is_visible" => 0,
            "text" => $linkText,
            "display_position" => $displayPosition
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::table("coreg_plugins")
            ->where(["name" => "ISay"])
            ->delete();
    }
}
