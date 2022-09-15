<?php namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ReferralAwardShare
 *
 * @ORM\Table(name="referral_award_share", indexes={@ORM\Index(name="fk_referral_award_share_referral_award_idx", columns={"referral_award_id"})})
 * @ORM\Entity
 */
class ReferralAwardShare
{
    /**
     * @var string
     *
     * @ORM\Column(name="referral_channel", type="string", length=0, nullable=false, options={"default"="Link","comment"="Name of social channel."})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $referralChannel = 'Link';

    /**
     * @var int
     *
     * @ORM\Column(name="share_number", type="smallint", nullable=false, options={"comment"="Number of shares required for the reward."})
     */
    private $shareNumber = '0';

    /**
     * @var ReferralAward
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="ReferralAward")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referral_award_id", referencedColumnName="referral_award_id")
     * })
     */
    private $referralAward;

    const CHANNEL_FACEBOOK = "Facebook";
    const CHANNEL_TWITTER = "Twitter";
    const CHANNEL_PINTEREST = "Pinterest";
    const CHANNEL_WHATSAPP = "Whatsapp";
    const CHANNEL_SMS = "SMS";
    const CHANNEL_EMAIL = "Email";
    const CHANNEL_LINK = "Link";


    public function __construct() {
        $this->referralAwardId = 0;
        $this->referralChannel = "";
        $this->shareCount = 0;
    }


    public function getReferralAwardId(){
        return $this->referralAwardId;
    }

    public function setReferralAwardId($referralAwardId){
        $this->referralAwardId = $referralAwardId;
    }

    public function getReferralChannel(){
        return $this->referralChannel;
    }

    public function setReferralChannel($referralChannel){
        $this->referralChannel = $referralChannel;
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

    public function getShareCount(){
        return $this->shareCount;
    }

    public function setShareCount($shareCount){
        $this->shareCount = $shareCount;
    }

    public function toArray() {
        return array(
            "referral_award_id" => $this->getReferralAwardId(),
            "referral_channel" => $this->getReferralChannel(),
            "share_count" => $this->getShareCount()
        );
    }
}
