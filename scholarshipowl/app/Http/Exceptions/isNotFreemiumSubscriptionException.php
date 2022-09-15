<?php namespace App\Http\Exceptions;

class isNotFreemiumSubscriptionException extends \Exception
{
    protected $message = "Can't set this package. It's not a freemium package.";
}
