<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Domain extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'domain.service';
    }
}
