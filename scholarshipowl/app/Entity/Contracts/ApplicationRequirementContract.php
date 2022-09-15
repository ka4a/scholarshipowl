<?php namespace App\Entity\Contracts;
use App\Entity\Account;
use App\Entity\Scholarship;

/**
 * Filled scholarship requirement interface.
 */
interface ApplicationRequirementContract
{
    /**
     * @return RequirementContract
     */
    public function getRequirement();

    /**
     * @param RequirementContract $requirement
     *
     * @return $this
     */
    public function setRequirement(RequirementContract $requirement);

    /**
     * @param Scholarship $scholarship
     *
     * @return $this
     */
    public function setScholarship(Scholarship $scholarship);

    /**
     * @return Scholarship
     */
    public function getScholarship();

    /**
     * @return Account
     */
    public function getAccount();
}
