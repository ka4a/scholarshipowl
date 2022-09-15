<?php namespace App\Services;

use App\Entity\Domain;

class DomainService
{
    protected $domain = null;

    /**
     * @param $domain
     */
    public function set($domain)
    {
        $this->domain = Domain::convert($domain);
    }

    /**
     * @return bool
     */
    public function isScholarshipOwl()
    {
        return $this->get()->is(Domain::SCHOLARSHIPOWL);
    }

    /**
     * @return \App\Entity\Domain
     */
    public function get() : Domain
    {
        if ($this->domain === null) {
            $this->domain = Domain::find(Domain::SCHOLARSHIPOWL);
        }

        return $this->domain;
    }
}
