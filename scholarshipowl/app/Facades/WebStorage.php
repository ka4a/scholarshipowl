<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WebStorage extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return \App\Services\WebStorage::class;
    }
}
