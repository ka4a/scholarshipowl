<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Setting;

class SettingRedirectEmptySelectPage extends Migration
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
            Setting::GROUP_SCHOLARSHIPS,
            Setting::SETTING_OFFER_WALL_AFTER_EMPTY_SELECT,
            'Redirect if already applied on YDIT scholarship'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_OFFER_WALL_AFTER_EMPTY_SELECT);
    }
}
