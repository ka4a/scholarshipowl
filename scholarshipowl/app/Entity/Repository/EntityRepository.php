<?php namespace App\Entity\Repository;

use App\Entity\Exception\EntityNotFound;

use Doctrine\ORM\EntityRepository as DefaultEntityRepository;
use Doctrine\ORM\Query;
use ScholarshipOwl\Doctrine\ORM\QueryIterator;

class EntityRepository extends DefaultEntityRepository
{

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
        return parent::getEntityManager();
    }

    /**
     * @param $id
     *
     * @return object
     * @throws EntityNotFound
     */
    public function findById($id)
    {
        if ( ! ($entity = $this->find($id))) {
            throw new EntityNotFound($this->getClassName(), ['id' => $id]);
        }

        return $entity;
    }

    /**
     * @param object|array|int $entity
     *
     * @return object
     */
    public function convert($entity)
    {
        return is_object($entity) && get_class($entity) === $this->getClassName() ? $entity : $this->findById($entity);
    }

    /**
     * @return string
     */
    public function alias()
    {
        return strtolower(substr(get_class($this), 0, 3));
    }

    /**
     * @param string    $alias
     * @param mixed     $select
     * @param int       $hydrate
     * @param int       $limit
     * @param int       $start
     *
     * @return QueryIterator
     */
    public function queryIterator($alias, $select = null, $hydrate = Query::HYDRATE_OBJECT, $limit = 1000, $start = 0)
    {
        $qb = $this->createQueryBuilder($alias);

        if ($select) {
            $qb->select($select);
        }

        return QueryIterator::create($qb->getQuery(), $limit, $start, $hydrate);
    }
}
