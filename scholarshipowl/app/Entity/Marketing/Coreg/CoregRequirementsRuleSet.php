<?php
namespace App\Entity\Marketing\Coreg;

use App\Entity\Marketing\CoregPlugin;
use App\Entity\Traits\Dictionary;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CoregRequirementsRuleSet
 *
 * @ORM\Table(name="coreg_requirements_rule_set")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class CoregRequirementsRuleSet
{
    const CACHE_TAG = 'coreg_requirements_rule_set';

    const TYPE_ALL = "AND";
    const TYPE_ANY = "OR";

    const TABLE = "profile";

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
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type = self::TYPE_ALL;

    /**
     * @var string
     *
     * @ORM\Column(name="table_name", type="string", length=255, nullable=true)
     */
    private $tableName = self::TABLE;

    /**
     * @var integer
     *
     * @ORM\Column(name="coreg_id", type="integer", length=255, nullable=true)
     */
    private $coreg;

    /**
     * @var ArrayCollection|\App\Entity\Marketing\Coreg\CoregRequirementsRule[]
     *
     * @ORM\OneToMany(targetEntity="CoregRequirementsRule", mappedBy="coregRequirementsRuleSet", cascade={"persist"})
     */
    private $coregRequirementsRule;

    public function __construct()
    {
//        $this->coregRequirementsRule = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    /**
     * @return CoregRequirementsRule|CoregRequirementsRule[]
     */
    public function getCoregRequirementsRule()
    {
        return $this->coregRequirementsRule;
    }

    /**
     * @param CoregRequirementsRule|CoregRequirementsRule[] $coregRequirementsRule
     */
    public function setCoregRequirementsRule($coregRequirementsRule)
    {
        $this->coregRequirementsRule = $coregRequirementsRule;
    }

    /**
     * @param CoregRequirementsRule $coregRequirementsRule
     *
     * @return $this
     */
    public function addRedirectRule(CoregRequirementsRule $coregRequirementsRule)
    {
        if (!$this->coregRequirementsRule->contains($coregRequirementsRule)) {
            $coregRequirementsRule->setCoregRequirementsRuleSet($this);
            $this->coregRequirementsRule->add($coregRequirementsRule);
        }

        return $this;
    }

    public static function getRedirectRulesSetTypes() {
        return array(
            self::TYPE_ALL => "All",
            self::TYPE_ANY => "Any"
        );
    }

    /**
     * @return CoregPlugin
     */
    public function getCoreg()
    {
        return $this->coreg;
    }

    /**
     * @param integer $coregId
     *
     * @return CoregRequirementsRuleSet
     */
    public function setCoreg($coregId)
    {
        $this->coreg = $coregId;

        return $this;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     * @ORM\PostRemove()
     */
    public function flushCacheTag()
    {
        \Cache::tags([self::CACHE_TAG])->flush();
    }

    public function __sleep()
    {
        // make doctrine load all rules to PersistentCollection before serialization
        iterator_to_array($this->coregRequirementsRule);

        return [
            'id',
            'type',
            'tableName',
            'coreg',
            'coregRequirementsRule'
        ];
    }
}
