<?php namespace App\Facades;

use App\Entity\Repository\EntityRepository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\EntityManagerInterface;
use LaravelDoctrine\ORM\Facades\EntityManager as FacadeEntityManager;
use LaravelDoctrine\ORM\Facades\Registry;

class EntityManager extends FacadeEntityManager
{

    /**
     * @return DoctrineEntityManager
     */
    public static function getFacadeRoot()
    {
        return parent::getFacadeRoot();
    }

    /**
     * @param string $entityClass
     *
     * @return EntityRepository
     */
    public static function getRepository(string $entityClass)
    {
        return static::getFacadeRoot()->getRepository($entityClass);
    }

    /**
     * @param string $entityClass
     * @param        $id
     *
     * @return object
     */
    public static function findById(string $entityClass, $id)
    {
        return static::getRepository($entityClass)->findById($id);
    }

    /**
     * @param object|array|null $entity
     *
     * @throws DBALException
     */
    public static function flush($entity = null)
    {
        try {
            static::getFacadeRoot()->flush($entity);
        } catch (DBALException $e) {
            static::reopen();
            throw $e;
        }
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    public static function reopen()
    {
        $entityManager = static::getFacadeRoot();
        if (!$entityManager->isOpen()) {
            static::swap($entityManager->create(
                $entityManager->getConnection(),
                $entityManager->getConfiguration()
            ));
        }
    }

    /**
     * @return EntityManagerInterface
     */
    public static function emails()
    {
        return static::$app->make(ManagerRegistry::class)->getManager('emails');
    }
}
