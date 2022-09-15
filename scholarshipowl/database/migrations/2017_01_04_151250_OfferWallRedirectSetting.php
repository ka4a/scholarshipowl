<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Setting;

class OfferWallRedirectSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::create(
            Setting::TYPE_STRING,
            'Scholarships',
            Setting::SETTING_OFFER_WALL_AFTER_APPLY,
            'Link redirect after /select page'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_OFFER_WALL_AFTER_APPLY);
    }
}
