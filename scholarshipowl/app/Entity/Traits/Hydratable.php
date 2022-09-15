<?php namespace App\Entity\Traits;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

trait Hydratable
{
    /**
     * @param array $data
     *
     * @return $this
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function hydrate(array $data)
    {
        /**
         * @var ClassMetadataInfo $metadata
         */
        $metadata = \EntityManager::getMetadataFactory()->getMetadataFor(static::class);

        foreach ($data as $columnName => $value) {
            if ($property = $metadata->getFieldName($columnName)) {
                $setter = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $property)));

                if (method_exists($this, $setter)) {

                    $type = $metadata->fieldMappings[$property]['type'] ?? null;

                    if ($type && in_array($type, ['datetime', 'date']) && ! $value instanceof \DateTime) {
                        $value = new \DateTime($value);
                    }

                    $this->$setter($value);
                }
            }
        }

        return $this;
    }
}
