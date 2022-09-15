<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ScholarshipService extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() { return 'scholarship.service'; }
}
