<?php namespace App\Contracts;

interface CachableEntity
{
    /**
     * @return string
     */
    public function cacheTag() : string;
}
