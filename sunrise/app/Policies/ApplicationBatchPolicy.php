<?php namespace App\Policies;

class ApplicationBatchPolicy
{
    public function restCreate($user)
    {
        dd($user);
    }
}
