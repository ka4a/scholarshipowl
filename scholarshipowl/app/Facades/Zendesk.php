<?php
/**
 * Created by PhpStorm.
 * User: vadimkrutov
 * Date: 14/06/16
 * Time: 16:46
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Zendesk extends Facade
{
    /**
     * Return facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'zendesk'; }
}

