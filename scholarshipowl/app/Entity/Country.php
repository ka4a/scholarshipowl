<?php namespace App\Entity;

use App\Contracts\DictionaryContract;
use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country implements DictionaryContract
{
    use Dictionary {
        options as protected dictOptions;
    }

    const USA = 1;
    const CANADA = 41;

    const COOKIE_USER_COUNTRY = '_so_uc';

    const PARAM_USER_COUNTRY = 'DEBUG_UC';

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=127, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abbreviation", type="string", length=7, nullable=false)
     */
    private $abbreviation;

    /**
     * @return Country
     */
    public static function usa()
    {
        return static::find(static::USA);
    }

    /**
     * @param array $options
     * @param bool  $us
     *
     * @return array
     */
    public static function options(array $options = [], $us = true)
    {
        $options = self::dictOptions($options);

        if ($us === false) {
            foreach ($options as $id => $name) {
                if ($id === Country::USA) {
                    unset($options[$id]);
                }
            }
        }

        return $options;
    }

    /**
     * @param $code
     *
     * @return null|Country
     */
    public static function findByCountryCode($code)
    {
        return \EntityManager::getRepository(static::class)->findOneBy(['abbreviation' => $code]);
    }

    /**
     * @return mixed
     */
    public static function getCountryCodeByIP()
    {
        if (request()->has(static::PARAM_USER_COUNTRY)) {
            return request()->get(static::PARAM_USER_COUNTRY);
        }

        if (request()->hasCookie(static::COOKIE_USER_COUNTRY)) {
            return request()->cookie(static::COOKIE_USER_COUNTRY);
        }

        return request()->server('HTTP_CF_IPCOUNTRY', 'US');
    }

    /**
     * @return string
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }
}

