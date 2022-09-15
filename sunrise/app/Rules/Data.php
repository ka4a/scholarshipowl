<?php

namespace App\Rules;

use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Pz\Doctrine\Rest\Contracts\JsonApiResource;

class Data implements Rule
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string|array
     */
    protected $rule;

    /**
     * @var null|string
     */
    protected $class;

    /**
     * Create a new rule instance.
     *
     * @param string            $jsonApiResource
     * @param string|array|null $rule
     */
    public function __construct($jsonApiResource, $rule = null)
    {
        if (!isset(class_implements($jsonApiResource)[JsonApiResource::class])) {
            throw new \RuntimeException('Class must be JsonApiResource type.');
        }

        $this->class = $jsonApiResource;
        $this->type = call_user_func([$jsonApiResource, 'getResourceKey']);
        $this->rule = $rule;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function passes($attribute, $value)
    {
        if (is_array($value) && isset($value['type']) && isset($value['id'])) {
            return $value['type'] === $this->type;
        }

        if (is_array($value) && isset($value['data']) && is_array($value['data'])) {
            if (isset($value['data']['id']) && isset($value['data']['type'])) {
                $typeFits = $value['data']['type'] === $this->type;

                if (!is_null($this->class)) {
                    /** @var EntityManager $em */
                    $em = app(EntityManager::class);
                    $exists = $em->find($this->class, $value['data']['id']);

                    return $exists && $typeFits;
                }

                return $typeFits;
            }
        }

        if (!is_null($this->rule)) {
            return !Validator::make(['data' => $value], ['data' => $this->rule])->fails();
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field is invalid.';
    }
}
