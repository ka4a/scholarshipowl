<?php namespace App\Contracts;

interface LegalContentContract
{
    const TYPE_AFFIDAVIT = 'affidavit';
    const TYPE_TERMS_OF_USE = 'termsOfUse';
    const TYPE_PRIVACY_POLICY = 'privacyPolicy';

    /**
     * @param string $type
     * @return string
     */
    public function getContentByType($type);
}
