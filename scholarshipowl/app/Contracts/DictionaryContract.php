<?php namespace App\Contracts;

/**
 * Interface DictionaryContract
 *
 * @property mixed  $id
 * @property string $name
 */
interface DictionaryContract
{
    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();
}
