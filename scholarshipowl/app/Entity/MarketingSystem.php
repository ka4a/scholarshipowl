<?php namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MarketingSystem
 *
 * @ORM\Table(name="marketing_system")
 * @ORM\Entity
 */
class MarketingSystem
{
    const HAS_OFFERS = 1;

    /**
     * @var integer
     *
     * @ORM\Column(name="marketing_system_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $marketingSystemId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url_identifier", type="string", length=255, nullable=false)
     */
    private $urlIdentifier;

    /**
     * @return int
     */
    public function getMarketingSystemId()
    {
        return $this->marketingSystemId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrlIdentifier()
    {
        return $this->urlIdentifier;
    }
}

