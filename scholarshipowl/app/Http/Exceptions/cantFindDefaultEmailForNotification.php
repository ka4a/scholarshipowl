<?php namespace App\Http\Exceptions;

class cantFindDefaultEmailForNotification extends \Exception
{
    protected $message = "Can't find sales team account with email from config!";
}
