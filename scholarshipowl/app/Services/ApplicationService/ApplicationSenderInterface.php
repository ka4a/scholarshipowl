<?php namespace App\Services\ApplicationService;

use App\Entity\Account;
use App\Entity\Application;
use App\Entity\Scholarship;

interface ApplicationSenderInterface
{
    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return Scholarship
     */
    public function prepareScholarship(Scholarship $scholarship, Account $account) : Scholarship;

    /**
     * @param Scholarship $scholarship
     * @param Account     $account
     *
     * @return array
     */
    public function prepareSubmitData(Scholarship $scholarship, Account $account) : array;

    /**
     * @param Scholarship $scholarship
     * @param array $submitData
     * @param Application $application
     * @return mixed
     */
    public function sendApplication(Scholarship $scholarship, array $submitData, Application $application);
}
