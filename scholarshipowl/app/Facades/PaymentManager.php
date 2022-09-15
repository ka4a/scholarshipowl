<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentManager extends Facade
{

    /**
     * @return string
     */
    protected static function getFacadeAccessor() { return 'payment.manager'; }

    /**
     * @return \App\Payment\RemotePaymentManager
     */
    public static function remote()
    {
        return static::$app->make('payment.remote_manager');
    }
}
