<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class LogHasoffers extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() { return 'log.hasoffers'; }
}