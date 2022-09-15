<?php namespace App\Traits;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Illuminate\Validation\Rule;
use Pz\Doctrine\Rest\Exceptions\RestException;
use Pz\Doctrine\Rest\RestRepository;

trait DictionaryEntity
{
    /**
     * @var array
     */
    protected static $dictOptions;

    /**
     * @var RestRepository
     */
    protected static $repository;

    /**
     * @return array
     */
    public static function exists()
    {
        return ['sometimes', 'required', 'numeric', Rule::exists(static::class, 'id')];
    }

    /**
     * @return RestRepository
     */
    public static function repository()
    {
        if (static::$repository === null) {
            /** @var EntityManager $em */
            $em = app(EntityManager::class);
            static::$repository = $em->getRepository(static::class);
        }

        return static::$repository;
    }

    /**
     * @param mixed $id
     * @return object|static
     * @throws \Doctrine\ORM\ORMException
     */
    public static function find($id)
    {
        /** @var EntityManager $em */
        $em = app(EntityManager::class);
        return $em->getReference(static::class, $id);
    }

    /**
     * @param array $criteria
     *
     * @return static|null
     */
    public static function findOneBy(array $criteria)
    {
        return static::repository()->findOneBy($criteria);
    }

    /**
     * @param $name
     *
     * @return null|object
     */
    public static function findByName($name)
    {
        return static::repository()->findOneBy(['name' => $name]);
    }

    /**
     * @param $entity
     *
     * @return static
     */
    public static function convert($entity)
    {
        return ($entity instanceof static) ? $entity : static::find($entity);
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public static function options(array $criteria = [], $empty = false)
    {
        if (empty($criteria) && static::$dictOptions !== null) {
            return static::$dictOptions;
        }

        $options = [];

        if ($empty) {
            $options = [null => $empty];
        }

        /** @var DictionaryEntity $option */
        foreach (static::repository()->findBy($criteria) as $option) {
            $options[$option->getId()] = $option->getName();
        }

        if (empty($criteria)) static::$dictOptions = $options;

        return $options;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
    * @param int $id
    *
    * @return bool
    */
    public function is($id) : bool
    {
        return $this->getId() === $id;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function not($id) : bool
    {
        return $this->getId() !== $id;
    }
}
