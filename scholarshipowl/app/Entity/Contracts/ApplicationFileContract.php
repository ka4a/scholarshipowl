<?php namespace App\Entity\Contracts;

use App\Entity\AccountFile;

interface ApplicationFileContract
{
    /**
     * @return AccountFile
     */
    public function getAccountFile();
}
