<?php namespace App\Facades;

use App\Services\CmsService;
use Illuminate\Support\Facades\Facade;

class CMS extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor() { return CmsService::class; }
}
