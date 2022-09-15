<?php namespace App\Contracts;

interface DictionaryEntityContract
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}
