<?php namespace App\Entity\Traits;

use App\Entity\Exception\EntityNotFound;
use App\Entity\MilitaryAffiliation;
use App\Entity\Repository\EntityRepository;
use Doctrine\ORM\Query;
use Illuminate\Validation\Rule;

trait Dictionary
{
    /**
     * @var array
     */
    protected static $loaded = false;

    /**
     * @var array
     */
    protected static $dictOptions;

    /**
     * @var EntityRepository
     */
    protected static $repository;

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    public static function exists()
    {
        return ['sometimes', 'required', 'numeric', Rule::exists(static::class, 'id')];
    }

    /**
     * @return \Illuminate\Validation\Rules\Exists
     */
    public static function existsByaAbbreviation()
    {
        return ['sometimes', 'required', 'string', Rule::exists(static::class, 'abbreviation')];
    }

    /**
     * @return EntityRepository
     */
    public static function repository()
    {
        return \EntityManager::getRepository(static::class);
    }

    /**
     * Load dictionary into static variable.
     */
    protected static function load()
    {
        if (!static::$loaded) {
            static::repository()->findAll();
        }
    }

    /**
     * @param mixed $id
     *
     * @return static
     */
    public static function find($id)
    {
        static::load();
        return static::repository()->findById($id);
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

        /** @var Dictionary $option */
        foreach (static::repository()->findBy($criteria) as $option) {
            $options[$option->getId()] = $option->getName();
        }

        if (empty($criteria)) static::$dictOptions = $options;

        return $options;
    }

    /**
     * @return int
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
    public function is(int $id) : bool
    {
        return $this->getId() === $id;
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function not(int $id) : bool
    {
        return $this->getId() !== $id;
    }
}
