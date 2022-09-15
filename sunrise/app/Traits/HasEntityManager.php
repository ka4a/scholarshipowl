<?php namespace App\Traits;

use Doctrine\ORM\EntityManager;

trait HasEntityManager
{
    /**
     * @return EntityManager
     */
    public function em()
    {
        return app(EntityManager::class);
    }
}
