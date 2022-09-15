<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\Setting;
use App\Services\SettingService;

class ZendeskPopupTimeoutSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::create(
            Setting::TYPE_INT,
            'Payment Popup',
            'zendesk.payment-popup.timeout',
            'Zendesk payment popup timeout',
            180
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete('zendesk.payment-popup.timeout');
    }
}
