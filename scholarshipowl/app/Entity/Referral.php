<?php namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * Referral
 *
 * @ORM\Table(name="referral", indexes={@ORM\Index(name="ix_referral_referral_account_id", columns={"referral_account_id"}), @ORM\Index(name="ix_referral_referred_account_id", columns={"referred_account_id"})})
 * @ORM\Entity
 */
class Referral
{
    const CHANNEL_FACEBOOK = "Facebook";
    const CHANNEL_TWITTER = "Twitter";
    const CHANNEL_PINTEREST = "Pinterest";
    const CHANNEL_WHATSAPP = "Whatsapp";
    const CHANNEL_SMS = "SMS";
    const CHANNEL_EMAIL = "Email";
    const CHANNEL_LINK = "Link";

    /**
     * @var string
     *
     * @ORM\Column(name="referral_channel", type="string", length=0, nullable=false, options={"default"="Link"})
     */
    private $referralChannel = 'Link';

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referral_account_id", referencedColumnName="account_id")
     * })
     */
    private $referralAccount;

    /**
     * @var Account
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referred_account_id", referencedColumnName="account_id")
     * })
     */
    private $referredAccount;

    /**
     * @return string
     */
    public function getReferralChannel(): string
    {
        return $this->referralChannel;
    }

    /**
     * @param string $referralChannel
     */
    public function setReferralChannel(string $referralChannel): void
    {
        $this->referralChannel = $referralChannel;
    }

    /**
     * @return Account
     */
    public function getReferralAccount()
    {
        return $this->referralAccount;
    }

    /**
     * @param Account $referralAccount
     */
    public function setReferralAccount($referralAccount): void
    {
        $this->referralAccount = $referralAccount;
    }

    /**
     * @return Account
     */
    public function getReferredAccount()
    {
        return $this->referredAccount;
    }

    /**
     * @param Account $referredAccount
     */
    public function setReferredAccount( $referredAccount): void
    {
        $this->referredAccount = $referredAccount;
    }

    public static function getReferralChannels() {
        return array(
            self::CHANNEL_FACEBOOK => "Facebook",
            self::CHANNEL_TWITTER => "Twitter",
            self::CHANNEL_PINTEREST => "Pinterest",
            self::CHANNEL_WHATSAPP => "WhatsApp",
            self::CHANNEL_SMS => "SMS",
            self::CHANNEL_EMAIL => "Email",
            self::CHANNEL_LINK => "Link",
        );
    }
}
