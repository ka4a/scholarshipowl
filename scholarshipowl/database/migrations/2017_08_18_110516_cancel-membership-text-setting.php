<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
Use App\Entity\Setting;

class CancelMembershipTextSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::create(
            Setting::TYPE_TEXT,
            Setting::GROUP_MEMBERSHIPS,
            Setting::SETTING_CANCEL_SUBSCRIPTION_TEXT,
            'Cancellation popup text (not free trial)',
            ''
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_CANCEL_SUBSCRIPTION_TEXT);
    }
}
