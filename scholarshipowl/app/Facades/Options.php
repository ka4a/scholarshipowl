<?php namespace App\Facades;

use App\Services\OptionsManager;
use Illuminate\Support\Facades\Facade;

class Options extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return OptionsManager::class;
    }
}
