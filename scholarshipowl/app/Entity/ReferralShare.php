<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReferralShare
 *
 * @ORM\Table(name="referral_share", indexes={@ORM\Index(name="fk_referral_share_account_idx", columns={"account_id"})})
 * @ORM\Entity
 */
class ReferralShare
{
    /**
     * @var integer
     *
     * @ORM\Column(name="referral_share_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $referralShareId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="referral_date", type="datetime", nullable=false)
     */
    protected $referralDate = 'CURRENT_TIMESTAMP';

    /**
     * @var string
     *
     * @ORM\Column(name="referral_channel", type="string", nullable=true)
     */
    protected $referralChannel;

    /**
     * @var Account
     * 
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    protected $account;

    /**
     * Get referralShareId
     *
     * @return integer
     */
    public function getReferralShareId()
    {
        return $this->referralShareId;
    }

    /**
     * Set referralDate
     *
     * @param \DateTime $referralDate
     *
     * @return ReferralShare
     */
    public function setReferralDate($referralDate)
    {
        $this->referralDate = $referralDate;

        return $this;
    }

    /**
     * Get referralDate
     *
     * @return \DateTime
     */
    public function getReferralDate()
    {
        return $this->referralDate;
    }

    /**
     * Set referralChannel
     *
     * @param string $referralChannel
     *
     * @return ReferralShare
     */
    public function setReferralChannel($referralChannel)
    {
        $this->referralChannel = $referralChannel;

        return $this;
    }

    /**
     * Get referralChannel
     *
     * @return string
     */
    public function getReferralChannel()
    {
        return $this->referralChannel;
    }

    /**
     * Set account
     *
     * @param \App\Entity\Account $account
     *
     * @return ReferralShare
     */
    public function setAccount(\App\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \App\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}

