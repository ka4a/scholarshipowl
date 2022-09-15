<?php namespace App\Services\MauticService\Api;

class Smses extends \Mautic\Api\Smses
{
    /**
     * Fix Mautic PHP Library Bug.
     * Change method to "GET" as Mautic accepts "GET" method.
     * 
     * @inheritdoc
     */
    public function sendToContact($id, $contactId)
    {
        return $this->makeRequest($this->endpoint.'/'.$id.'/contact/'.$contactId.'/send');
    }
}
