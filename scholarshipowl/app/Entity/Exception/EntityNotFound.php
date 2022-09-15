<?php namespace App\Entity\Exception;

class EntityNotFound extends \RuntimeException
{
    /**
     * NotFound constructor.
     *
     * @param string     $entityClass
     * @param array      $criteria
     * @param int        $code
     * @param \Exception $previous
     */
    public function __construct(string $entityClass, array $criteria = array(), $code = 0, \Exception $previous = null)
    {
        parent::__construct(
            sprintf("Entity not found: %s\nCriteria: %s", $entityClass, doctrine_dump($criteria, 2, true, false)),
            $code,
            $previous
        );
    }
}
