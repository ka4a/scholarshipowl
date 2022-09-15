<?php namespace App\Entity\Contracts;

use App\Entity\Traits\Dictionary;

/**
 * Scholarship requirement interface.
 */
interface RequirementContract
{
    /**
     * @return Dictionary
     */
    public function getRequirementName();

    /**
     * @return string
     */
    public function getApplicationClass();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return int
     */
    public function getId();

    /**
     * @return int
     */
    public function getExternalId();

    /**
     * @return int
     */
    public function getExternalIdPermanent();
}
