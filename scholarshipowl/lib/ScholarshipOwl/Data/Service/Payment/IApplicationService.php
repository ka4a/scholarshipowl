<?php

/**
 * IApplicationService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\ApplicationEssay;


interface IApplicationService {
	// Get Applications Functions
	public function getApplications($accountId); // Moved To Scholarship/IApplicationService
	public function getApplicationsCount($accountId); // Moved To Scholarship/IStatisticService
    public function getSubmittedApplicationsCount($accountId);
    public function getSubmittedApplicationsWithRequirementsCount($accountId);
    public function getLastSubmittedApplicationWithEssay($accountId);

	// API Functions
	public function getApplicationScholarshipsData($accountId);

	public function savePendingApplications($accountId, $scholarshipIds, $subscriptionId = null);
	public function saveNeedMoreInfoApplications($accountId, $scholarshipIds, $subscriptionId = null);

	public function getPendingApplications();
	public function getNeedMoreInfoApplications($accountId);
	public function changeApplicationStatus($accountId, $scholarshipId, $applicationStatusId);

	public function getApplicationsAmount($accountIds);
}
