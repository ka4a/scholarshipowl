<?php namespace App\Http\Exceptions;

class FreemiumSubscriptionAlreadySet extends \Exception
{
    protected $message = 'Freemium package is already set to this account.';
}
