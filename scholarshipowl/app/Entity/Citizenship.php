<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Citizenship
 *
 * @ORM\Table(name="citizenship", indexes={@ORM\Index(name="ix_citizenship_country_id", columns={"country_id"})})
 * @ORM\Entity
 */
class Citizenship implements DictionaryContract
{
    use Dictionary;

    const CITIZENSHIP_USA = 1;
    const CITIZENSHIP_CANADA = 43;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="citizenship_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="country_id")
     * })
     */
    private $country;

    /**
     * @return Citizenship
     */
    public static function usa()
    {
        return static::find(static::CITIZENSHIP_CANADA);
    }

    /**
     * @param $code
     *
     * @return null|Citizenship
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public static function findByCountryCode($code)
    {
        return \EntityManager::getRepository(static::class)
            ->createQueryBuilder('ct')
            ->join('ct.country', 'c')
            ->where('c.abbreviation = :code')
            ->setParameter('code', $code)
            ->setMaxResults(1)->getQuery()
            ->getSingleResult();
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }
}

