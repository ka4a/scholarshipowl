<?php

namespace ScholarshipOwl\Domain\Repository;

use ScholarshipOwl\Data\Entity\AbstractEntity;
use Illuminate\Database\Query\Builder;

class AbstractRepository implements IRepository
{

    /**
     * @var string
     */
    protected $tableName = null;

    /**
     * @var string
     */
    private $entityClass = null;

    public function __construct()
    {
        if ($this->tableName === null) {
            throw new \LogicException("Missing 'tableName' property value.");
        }
    }

    /**
     * @return Builder
     */
    public function getBaseQuery()
    {
        return \DB::table($this->tableName);
    }

    /**
     * @param array $filters
     * @return AbstractEntity[]
     */
    public function findAll(array $filters = array())
    {
        $entities = array();

        $query = $this->applyFilters($this->getBaseQuery(), $filters);

        if ($result = $query->get()) {
            foreach ($result as $row) {
                $entities[] = $this->newEntity((array) $row);
            }
        }

        return $entities;
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters)
    {
        foreach ($filters as $filter) {
            if (isset($filter['operator']) && isset($filter['value']) && isset($filter['field'])) {
                $field = $filter['field'];
                $value = $filter['value'];

                if ($value instanceof \DateTime) {
                    $value = $value->format('Y-m-d H:i:s');
                }

                switch ($filter['operator']) {

                    case '=':
                    case 'eq':
                        $query->where($field, '=', $value);
                        break;

                    case '<':
                    case 'lt':
                        $query->where($field, '<', $value);
                        break;

                    case '>':
                    case 'gt':
                        $query->where($field, '>', $value);
                        break;

                    case 'in':
                        $query->whereIn($field, $value);
                        break;

                    default:
                        break;
                }
            }
        }

        return $query;
    }

    /**
     * @param array $data
     * @return AbstractEntity
     * @throws \Exception
     */
    public function newEntity(array $data = null)
    {
        $entityClass = $this->getEntityClass();

        /** @var AbstractEntity $entity */
        $entity = new $entityClass();
        $entity->loadFromRow($data);

        return $entity;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getEntityClass()
    {
        if ($this->entityClass === null) {
            throw new \LogicException("Entity class not set.");
        }

        return $this->entityClass;
    }

    /**
     * @param string $class
     * @return $this
     * @throws \Exception
     */
    public function setEntityClass($class)
    {
        $abstractClass = "\\ScholarshipOwl\\Data\\Entity\\AbstractEntity";

        if (!class_exists($class)) {
            throw new \Exception(sprintf("Entity class '%s' not exists.", $class));
        }

        if (!is_subclass_of($class, $abstractClass, true)) {
            throw new \Exception(sprintf("Entity class should extend from '%s'", $abstractClass));
        }

        $this->entityClass = $class;

        return $this;
    }

}