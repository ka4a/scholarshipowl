<?php namespace App\Entity;

use App\Entity\Repository\EntityRepository;

class AbstractEntity
{
    /**
     * @return EntityRepository
     */
    public static function repository()
    {
        return \EntityManager::getRepository(static::class);
    }
}
