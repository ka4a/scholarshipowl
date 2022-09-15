<?php namespace App\Entity;



use App\Entity\Traits\Dictionary;
use Doctrine\ORM\Mapping as ORM;

/**
 * ReferralAwardType
 *
 * @ORM\Table(name="referral_award_type")
 * @ORM\Entity
 */
class ReferralAwardType
{
    use Dictionary;
    /**
     * @var bool
     *
     * @ORM\Column(name="referral_award_type_id", type="boolean", nullable=false, options={"comment"="Primary key"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $referralAwardTypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, options={"comment"="Referral award type name."})
     */
    private $name;

    const NUMBER_OF_REFERRALS = 1;
    const NUMBER_OF_PAID_REFERRALS = 2;
    const NUMBER_OF_SHARES = 3;

    public function __construct($referralAwardTypeId = null) {
        $this->setReferralAwardTypeId($referralAwardTypeId);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @return bool
     */
    public function getReferralAwardTypeId(){
        return $this->referralAwardTypeId;
    }

    public function setReferralAwardTypeId($referralAwardTypeId){
        $this->referralAwardTypeId = $referralAwardTypeId;

        $types = self::getReferralAwardTypes();
        if(array_key_exists($referralAwardTypeId, $types)) {
            $this->name = $types[$referralAwardTypeId];
        }
    }

    /**
     * @return array
     */
    public static function getReferralAwardTypes() {
        return array(
            self::NUMBER_OF_REFERRALS => "Number Of Referrals",
            self::NUMBER_OF_PAID_REFERRALS => "Number Of Paid Referrals",
            self::NUMBER_OF_SHARES => "Number Of Shares"
        );
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->name;
    }

    /**
     * @return array
     */
    public function toArray() {
        return array(
            "referral_award_type_id" => $this->getReferralAwardTypeId(),
            "name" => $this->getName()
        );
    }
}
