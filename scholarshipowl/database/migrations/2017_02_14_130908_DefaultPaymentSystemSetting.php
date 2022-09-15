<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Entity\PaymentMethod;
use App\Entity\Setting;

class DefaultPaymentSystemSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Setting::create(
            Setting::TYPE_SELECT,
            Setting::GROUP_PAYMENT_POPUP,
            Setting::SETTING_PAYMENT_POPUP_DEFAULT_PAYMENT_METHOD,
            'Default payment method in popup',
            PaymentMethod::BRAINTREE,
            PaymentMethod::BRAINTREE,
            [
                PaymentMethod::BRAINTREE => 'Braintree',
                PaymentMethod::RECURLY => 'Recurly',
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Setting::delete(Setting::SETTING_PAYMENT_POPUP_DEFAULT_PAYMENT_METHOD);
    }
}
