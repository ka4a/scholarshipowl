<?php

namespace App\Entity\Marketing;

use App\Entity\Traits\Dictionary;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * RedirectRulesSet
 *
 * @ORM\Table(name="redirect_rules_set")
 * @ORM\Entity
 */
class RedirectRulesSet {
    use Dictionary;

    const TYPE_ALL = "AND";
    const TYPE_ANY = "OR";

    const TABLE = "profile";

    /**
     * @var integer
     *
     * @ORM\Column(name="redirect_rules_set_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="table_name", type="string", length=255, nullable=true)
     */
    private $tableName;

    /**
     * @var ArrayCollection|RedirectRule[]
     *
     * @ORM\OneToMany(targetEntity="RedirectRule", mappedBy="redirectRulesSet", cascade={"persist"})
     */
    private $redirectRules;

    public function __construct()
    {
        $this->redirectRules = new ArrayCollection();
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
     * @return RedirectRule|RedirectRule[]
     */
    public function getRedirectRules()
    {

        return $this->redirectRules;
    }

    /**
     * @param RedirectRule|RedirectRule[] $redirectRules
     */
    public function setRedirectRules($redirectRules)
    {
        $this->redirectRules = $redirectRules;
    }

    /**
     * @param RedirectRule $redirectRule
     *
     * @return $this
     */
    public function addRedirectRule(RedirectRule $redirectRule)
    {
        if (!$this->redirectRules->contains($redirectRule)) {
            $redirectRule->setRedirectRulesSet($this);
            $this->redirectRules->add($redirectRule);
        }

        return $this;
    }

    public static function getRedirectRulesSetTypes() {
        return array(
            self::TYPE_ALL => "All",
            self::TYPE_ANY => "Any"
        );
    }
}
