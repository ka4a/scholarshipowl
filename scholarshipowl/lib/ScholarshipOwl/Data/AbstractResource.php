<?php namespace ScholarshipOwl\Data;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var object
     */
    protected $entity;

    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var null|array
     */
    protected $only;

    /**
     * Can be used to set extra fields to resource response.
     *
     * @var array
     */
    protected $extra = [];

    /**
     * @var string
     */
    protected $stringData = '';
    /**
     * @return array
     */
    public function toArray() : array
    {
        $result = [];

        foreach ($this->fields as $field => $resource) {
            if (!$this->display($field)) {
                continue;
            }

            if (method_exists($this->entity, $method = 'get' . ucfirst($field))) {
                $value = $this->entity->$method();

                switch (true) {

                    case is_object($value) && class_exists($resource):
                        /** @var static $resource */
                        $resource = new $resource();
                        $resource->setEntity($value);
                        $value = $resource->toArray();
                        break;

                    case is_string($value) && class_exists($resource):
                        /** @var static $resource */
                        $resource = new $resource();
                        $resource->setStringData($value);
                        $value = $resource->toArray()[0];
                        break;

                    case is_string($resource) && ($value instanceof \DateTime):
                        $value = $value->format($resource);
                        break;

                    case is_string($resource) && (function_exists($resource)):
                        $value = $resource($value);
                        break;

                    case is_object($value) && method_exists($value, $resource):
                        $value = $value->$resource();
                        break;

                    default:
                        break;
                }

                $result[$field] = $value;
            }
        }

        return $result;
    }

    /**
     * @param ResourceInterface $resource
     * @param                   $entity
     *
     * @return array
     */
    public static function entityToArray($entity, ResourceInterface $resource = null) : array
    {
        /** @var ResourceInterface $resource */
        $resource = $resource ?: new static();
        return $resource->setEntity($entity)->toArray();
    }

    /**
     * @param array $only
     *
     * @return $this
     */
    public function setOnly(array $only)
    {
        $this->only = $only;
        return $this;
    }

    /**
     * @param string $field
     *
     * @return array|null
     */
    public function only($field = null)
    {
        if ($field !== null) {
            if (is_array($this->only) && isset($this->only[$field]) && is_array($this->only[$field])) {
                return $this->only[$field];
            }

            return null;
        }

        return $this->only;
    }

    /**
     * @param string $field
     *
     * @return bool
     */
    public function display(string $field)
    {
        return !is_array($this->only()) || ($this->only[$field] ?? false);
    }

    /**
     * @param array|object $entity
     * @param array        $extra
     *
     * @return $this
     */
    public function setEntity($entity, array $extra = [])
    {
        if (is_array($entity) && is_object($entity[0])) {
            $extra += array_slice($entity, 1);
            $entity = array_shift($entity);
        }

        if (!is_object($entity)) {
            throw new \InvalidArgumentException('Entity should be object!');
        }

        $this->entity = $entity;
        $this->extra = $extra;

        return $this;
    }

    public function setStringData(string  $data){
        $this->stringData = $data;
    }
}
