<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \App\Entity\Setting;

class PaymentPopupBottomTextSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::createBoolean(
            Setting::SETTING_PAYMENT_POPUP_BOTTOM_TEXT_ENABLED,
            Setting::GROUP_PAYMENT_POPUP,
            'Bottom text display'
        );
        \Setting::create(
            Setting::TYPE_TEXT,
            Setting::GROUP_PAYMENT_POPUP,
            Setting::SETTING_PAYMENT_POPUP_BOTTOM_TEXT,
            'Bottom text'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_PAYMENT_POPUP_BOTTOM_TEXT);
        \Setting::delete(Setting::SETTING_PAYMENT_POPUP_BOTTOM_TEXT_ENABLED);
    }
}
