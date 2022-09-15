<?php namespace App\Entities\Traits;

use App\Contracts\LegalContentContract;

trait LegalContent
{
    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $privacyPolicy;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $termsOfUse;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $affidavit;

    /**
     * @deprecated
     *   Deprecated at 12/2018.
     *   Official rules moved to termsOfUse, remove this field later. As we may use it after rollback.
     *
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $rules;

    /**
     * @return string
     */
    public function getPrivacyPolicy()
    {
        return $this->privacyPolicy;
    }

    /**
     * @param string $privacyPolicy
     * @return $this
     */
    public function setPrivacyPolicy($privacyPolicy)
    {
        $this->privacyPolicy = $privacyPolicy;
        return $this;
    }

    /**
     * @return string
     */
    public function getTermsOfUse()
    {
        return $this->termsOfUse;
    }

    /**
     * @param string $termsOfUse
     * @return $this
     */
    public function setTermsOfUse($termsOfUse)
    {
        $this->termsOfUse = $termsOfUse;
        return $this;
    }

    /**
     * @return string
     */
    public function getAffidavit()
    {
        return $this->affidavit;
    }

    /**
     * @param string $affidavit
     * @return $this
     */
    public function setAffidavit($affidavit)
    {
        $this->affidavit = $affidavit;
        return $this;
    }

    /**
     * @param string $type
     * @return string
     */
    public function getContentByType($type)
    {
        switch ($type) {
            case LegalContentContract::TYPE_AFFIDAVIT:
                return $this->getAffidavit();
                break;
            case LegalContentContract::TYPE_TERMS_OF_USE:
                return $this->getTermsOfUse();
                break;
            case LegalContentContract::TYPE_PRIVACY_POLICY:
                return $this->getPrivacyPolicy();
                break;
            default:
                throw new \RuntimeException("Don't known content type '$type'");
                break;
        }
    }
}
