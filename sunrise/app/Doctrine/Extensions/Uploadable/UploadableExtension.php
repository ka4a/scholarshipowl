<?php namespace App\Doctrine\Extensions\Uploadable;

use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventManager;
use Doctrine\ORM\EntityManagerInterface;
use LaravelDoctrine\Extensions\GedmoExtension;

class UploadableExtension extends GedmoExtension
{
    /**
     * @param EventManager           $manager
     * @param EntityManagerInterface $em
     * @param Reader                 $reader
     */
    public function addSubscribers(EventManager $manager, EntityManagerInterface $em, Reader $reader = null)
    {
        $this->addSubscriber(app(PrivateListener::class), $manager, $reader);
        $this->addSubscriber(app(PublicListener::class), $manager, $reader);
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return [];
    }
}
