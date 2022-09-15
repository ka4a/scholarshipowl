<?php namespace App\Entity;

use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;
use LaravelDoctrine\Extensions\Timestamps\Timestamps;

/**
 * FeatureAbTest
 *
 * @ORM\Table(name="feature_ab_test", uniqueConstraints={@ORM\UniqueConstraint(name="feature_ab_test_name_unique", columns={"name"})}, indexes={@ORM\Index(name="feature_ab_test_feature_set_foreign", columns={"feature_set"})})
 * @ORM\Entity(repositoryClass="App\Entity\Repository\FeatureAbTestRepository")
 */
class FeatureAbTest
{
    use Dictionary;
    use Timestamps;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled = false;

    /**
     * @var array
     *
     * @ORM\Column(name="config", type="json_array", length=65535, nullable=false)
     */
    private $config;

    /**
     * @var FeatureSet
     *
     * @ORM\ManyToOne(targetEntity="FeatureSet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="feature_set", referencedColumnName="id")
     * })
     */
    private $featureSet;

    /**
     * FeatureAbTest constructor.
     *
     * @param string            $name
     * @param int|FeatureSet    $featureSet
     * @param array             $config
     */
    public function __construct(string $name, $featureSet, array $config)
    {
        $this->setName($name);
        $this->setFeatureSet($featureSet);
        $this->setConfig($config);
    }

    /**
     * @param $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param int|FeatureSet $featureSet
     *
     * @return $this
     */
    public function setFeatureSet($featureSet)
    {
        $this->featureSet = FeatureSet::convert($featureSet);
        return $this;
    }

    /**
     * @return FeatureSet
     */
    public function getFeatureSet()
    {
        return $this->featureSet;
    }
}

