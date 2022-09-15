<?php namespace App\Contracts;

interface RequirementFileContract
{
    /**
     * @return string
     */
    public function getFileExtension();

    /**
     * @return int
     */
    public function getMaxFileSize();
}
