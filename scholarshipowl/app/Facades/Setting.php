<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Setting extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'app.setting';
    }
}
