<?php
/**
 * Created by PhpStorm.
 * User: vadimkrutov
 * Date: 15/07/16
 * Time: 12:38
 */

namespace App\Entity\Resource;


use App\Entity\Admin\Admin;
use App\Entity\Admin\AdminRole;
use App\Entity\Annotations\AccessLimit;
use App\Entity\Annotations\Restricted;
use App\Entity\Country;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;

class Resource
{
    protected $entity;

    protected static $reader;

    protected $isCollectionEntity;

    /**
     * @param array $collection
     * @param string $resourceClass
     *
     * @return ArrayCollection|Resource[]
     */
    public static function getResourceCollection(array $collection, string $resourceClass = Resource::class)
    {
        $resourceCollection = new ArrayCollection();

        foreach ($collection as $entity) {
            $resourceCollection->add(new $resourceClass($entity, true));
        }

        return $resourceCollection;
    }

    /**
     * Method that loads annoation reader instance
     *
     * @return mixed
     */
    protected static function getReader()
    {
        if (static::$reader === null) {
            static::$reader = new AnnotationReader();
        }

        return static::$reader;
    }

    public function __construct($entity, $isCollectionEntity = false)
    {
        $this->entity = $entity;
        $this->isCollectionEntity = $isCollectionEntity;
    }

    public function __call($name, $arguments)
    {
        if ((substr($name, 0, 3) === 'get' || substr($name, 0, 2) === 'is') && method_exists($this->entity, $name)) {

            $value = call_user_func_array([$this->entity, $name], $arguments);

            if (null === ($admin = \Auth::user()) || !$admin instanceof Admin) {
                return $value;
            }

            if (is_scalar($value)) {
                $annotation = $this->getAnnotation($name);
                if ($annotation && $this->isRestricted($admin->getAdminRole()) && $this->isCollectionEntity) {
                    $value = $this->processEntityValue($value);
                } elseif ($annotation && $this->isBlocked($admin->getAdminRole())) {
                    $value = $this->processEntityValue($value);
                }

            } else if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            } else if ($value instanceof ArrayCollection) {
                $value = self::getResourceCollection($value->getValues());
            } else if (is_object($value)) {
                $value = new self($value, $this->isCollectionEntity);
            } else if (is_array($value)) {
                $value = self::getResourceCollection($value);
            }

            return $value;
        }
    }

    private function getAnnotation($getterName)
    {
        $reader = static::getReader();
        $property = lcfirst(substr($getterName, 3));
        $metaData = \EntityManager::getClassMetaData(get_class($this->entity));

        return isset($metaData->reflFields[$property]) ?
            $reader->getPropertyAnnotation($metaData->reflFields[$property], Restricted::class) : null;
    }

    private function processEntityValue($value)
    {
        if (strlen($value) > 4) {
            $value = substr($value, 0, 4) . '****';
        } else {
            $value = substr($value, 0, 1) . '***';
        }

        return $value;
    }

    protected function isRestricted(AdminRole $adminRole)
    {
       return $adminRole->getAccessLevel() === AdminRole::LEVEL_ACCESS_RESTRICTED;
    }

    protected function isBlocked(AdminRole $adminRole)
    {
        return $adminRole->getAccessLevel() === AdminRole::LEVEL_ACCESS_NO_DATA;
    }

    public function __toString()
    {
        if (method_exists($this->entity, '__toString')) {
            return $this->entity->__toString();
        }

        throw new \RuntimeException(sprintf('Object %s can\'t be converted to string!', static::class));
    }

    public function getEntity()
    {
        return $this->entity;
    }
}
