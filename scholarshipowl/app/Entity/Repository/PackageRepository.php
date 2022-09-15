<?php namespace App\Entity\Repository;

use App\Entity\Exception\EntityNotFound;
use App\Entity\Package;

/**
 * Class PackageRepository
 * @package App\Entity\Repository
 */
class PackageRepository extends EntityRepository
{

    /**
     * @param string $braintreePlan
     *
     * @return Package
     */
    public function findByBraintreePlan(string $braintreePlan): Package
    {
        $criteria = ['braintreePlan' => $braintreePlan];
        
        if (null === ($package = $this->findOneBy($criteria))) {
            throw new EntityNotFound(Package::class, $criteria);
        }

        return $package;
    }

    /**
     * @return array
     */
    public function findFreePackages()
    {
        return $this->findBy(['price' => 0]);
    }

    /**
     * @param int $packageId
     * @return string
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getButtonTextForPackage(int $packageId)
    {
        $buttonText = '';

        $cacheKey = sprintf('package_button_text_%d', $packageId);
        if (!$buttonText = \Cache::tags([Package::CACHE_TAG])->get($cacheKey)) {

            $content = $this->getEntityManager()->getConnection()->executeQuery(
                'select content from package_style where package_id = :packageId and element = "button";',
                ['packageId' => $packageId]
            )->fetch();
            if(is_array($content) && isset($content['content'])) {
                $buttonText = $content['content'];
                \Cache::tags([Package::CACHE_TAG])->put($cacheKey, $buttonText, 60 * 24 * 7);
            }
        }

        return $buttonText;
    }
}
