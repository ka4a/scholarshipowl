<?php namespace App\Entity;



use Doctrine\ORM\Mapping as ORM;

/**
 * ReferralAward
 *
 * @ORM\Table(name="referral_award", indexes={@ORM\Index(name="ix_referral_award_referral_award_type_id", columns={"referral_award_type_id"}), @ORM\Index(name="ix_referral_award_referred_package_id", columns={"referred_package_id"}), @ORM\Index(name="ix_referral_award_referral_package_id", columns={"referral_package_id"}), @ORM\Index(name="ix_referral_award_is_active", columns={"is_active"})})
 * @ORM\Entity
 */
class ReferralAward
{
    /**
     * @var int
     *
     * @ORM\Column(name="referral_award_id", type="integer", nullable=false, options={"unsigned"=true,"comment"="Primary key"})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $referralAwardId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false, options={"comment"="Referral award name."})
     */
    private $name;

    /**
     * @var string|null
     *
     * @ORM\Column(name="description", type="string", length=2045, nullable=true, options={"comment"="Referral award description."})
     */
    private $description = '';

    /**
     * @var string|null
     *
     * @ORM\Column(name="redirect_description", type="string", length=2045, nullable=true, options={"comment"="Referral award redirect description."})
     */
    private $redirectDescription = '';

    /**
     * @var int
     *
     * @ORM\Column(name="referrals_number", type="integer", nullable=false, options={"unsigned"=true,"comment"="Number of referrals needed for award."})
     */
    private $referralsNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=false, options={"comment"="Is referral award active."})
     */
    private $isActive = '0';

    /**
     * @var ReferralAwardType
     *
     * @ORM\ManyToOne(targetEntity="ReferralAwardType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referral_award_type_id", referencedColumnName="referral_award_type_id")
     * })
     */
    private $referralAwardType;

    /**
     * @var Package
     *
     * @ORM\ManyToOne(targetEntity="Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referral_package_id", referencedColumnName="package_id")
     * })
     */
    private $referralPackage;

    /**
     * @var Package
     *
     * @ORM\ManyToOne(targetEntity="Package")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="referred_package_id", referencedColumnName="package_id")
     * })
     */
    private $referredPackage;

    /**
     * @return int
     */
    public function getReferralAwardId()
    {
        return $this->referralAwardId;
    }

    /**
     * @param int $referralAwardId
     */
    public function setReferralAwardId($referralAwardId)
    {
        $this->referralAwardId = $referralAwardId;
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
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getRedirectDescription()
    {
        return $this->redirectDescription;
    }

    /**
     * @param string|null $redirectDescription
     */
    public function setRedirectDescription($redirectDescription)
    {
        $this->redirectDescription = $redirectDescription;
    }

    /**
     * @return int
     */
    public function getReferralsNumber()
    {
        return $this->referralsNumber;
    }

    /**
     * @param int $referralsNumber
     */
    public function setReferralsNumber(int $referralsNumber)
    {
        $this->referralsNumber = $referralsNumber;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return ReferralAwardType
     */
    public function getReferralAwardType()
    {
        return $this->referralAwardType;
    }

    /**
     * @param ReferralAwardType $referralAwardType
     */
    public function setReferralAwardType($referralAwardType)
    {
        $this->referralAwardType = ReferralAwardType::convert($referralAwardType);
    }

    /**
     * @return Package
     */
    public function getReferralPackage()
    {
        return $this->referralPackage;
    }

    /**
     * @param $referralPackage
     */
    public function setReferralPackage($referralPackage)
    {
        $this->referralPackage = $referralPackage;
    }

    /**
     * @return Package
     */
    public function getReferredPackage()
    {
        return $this->referredPackage;
    }

    /**
     * @param Package $referredPackage
     */
    public function setReferredPackage($referredPackage)
    {
        $this->referredPackage = $referredPackage;
    }


    public function toArray() {
        return array(
            "referral_award_id" => $this->getReferralAwardId(),
            "referral_award_type_id" => $this->getReferralAwardType()->getReferralAwardTypeId(),
            "name" => $this->getName(),
            "description" => $this->getDescription(),
            "redirect_description" => $this->getRedirectDescription(),
            "referrals_number" => $this->getReferralsNumber(),
            "referral_package_id" => $this->getReferralPackage()->getPackageId(),
            "referred_package_id" => $this->getReferredPackage()->getPackageId(),
            "is_active" => $this->isActive()
        );
    }

}
