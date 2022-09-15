<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbTest
 *
 * @ORM\Table(name="ab_test", indexes={@ORM\Index(name="ix_ab_test_start_date", columns={"start_date"}), @ORM\Index(name="ix_ab_test_end_date", columns={"end_date"}), @ORM\Index(name="ix_ab_test_is_active", columns={"is_active"})})
 * @ORM\Entity
 */
class AbTest
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ab_test_id", type="integer", precision=0, scale=0, nullable=false, unique=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $abTestId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, precision=0, scale=0, nullable=false, unique=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=2045, precision=0, scale=0, nullable=false, unique=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", precision=0, scale=0, nullable=false, unique=false)
     */
    private $endDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $isActive;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Account", inversedBy="abTest")
     * @ORM\JoinTable(name="ab_test_account",
     *   joinColumns={
     *     @ORM\JoinColumn(name="ab_test_id", referencedColumnName="ab_test_id", nullable=true)
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="account_id", referencedColumnName="account_id", nullable=true)
     *   }
     * )
     */
    private $account;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->account = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get abTestId
     *
     * @return integer
     */
    public function getAbTestId()
    {
        return $this->abTestId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return AbTest
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AbTest
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return AbTest
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return AbTest
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return AbTest
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Add account
     *
     * @param \App\Entity\Account $account
     *
     * @return AbTest
     */
    public function addAccount(\App\Entity\Account $account)
    {
        $this->account[] = $account;

        return $this;
    }

    /**
     * Remove account
     *
     * @param \App\Entity\Account $account
     */
    public function removeAccount(\App\Entity\Account $account)
    {
        $this->account->removeElement($account);
    }

    /**
     * Get account
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccount()
    {
        return $this->account;
    }
}

