<?php namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Mailbox extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'mailbox';
    }
}
