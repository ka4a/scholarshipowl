<?php namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ReferralAwardAccount
 *
 * @ORM\Table(name="referral_award_account", indexes={@ORM\Index(name="ix_referral_award_account_account_id", columns={"account_id"}), @ORM\Index(name="ix_referral_award_account_awarded_date", columns={"awarded_date"}), @ORM\Index(name="ix_referral_award_account_referral_award_id", columns={"referral_award_id"})})
 * @ORM\Entity
 */
class ReferralAwardAccount
{
    const REFERRAL_AWARD = "referral";
    const REFERRED_AWARD = "referred";

    /**
     * @var string
     *
     * @ORM\Column(name="award_type", type="string", length=0, nullable=false, options={"default"="referral","comment"="Type of awarded reward."})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $awardType = 'referral';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="awarded_date", type="datetime", nullable=false, options={"default"="0000-00-00 00:00:00","comment"="Date when accounts are awarded."})
     */
    private $awardedDate = '0000-00-00 00:00:00';

    /**
     * @var \Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="account_id", referencedColumnName="account_id")
     * })
     */
    private $account;

    /**
     * @var \ReferralAward
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="ReferralAward")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referral_award_id", referencedColumnName="referral_award_id")
     * })
     */
    private $referralAward;


}
