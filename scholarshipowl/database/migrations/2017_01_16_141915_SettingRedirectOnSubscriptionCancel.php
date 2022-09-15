<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Entity\Setting;

class SettingRedirectOnSubscriptionCancel extends Migration
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
            Setting::GROUP_MEMBERSHIPS,
            Setting::SETTING_REDIRECT_AFTER_SUBSCRIPTION_CANCEL,
            'Redirect after free trial subscription cancelled'
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_REDIRECT_AFTER_SUBSCRIPTION_CANCEL);
    }
}
