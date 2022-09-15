<?php

/**
 * IApplicationService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;


interface IApplicationService {
	// Getting Applications Functions
	public function getApplication($accountId, $scholarshipId);
	public function getApplications($accountId);
    public function getApplicationsByStatuses($accountId, $statuses = array());
	public function getApplicationsEssays($accountId, $essayIds = array());
	public function getApplicationsEssaysIds($accountId);
	public function getApplicationEssayIdsFromFiles($accountId, $scholarshipId);
	public function getApplicationEssayFileIds($accountId, $essayId);
	public function getApplicationsByStatus($status, $deadline = null, $active = null);


	// Editing Functions
	public function changeApplicationStatus($accountId, $scholarshipId, $applicationStatusId, $submitedData = null, $comment = null);


	// Applying Functions
	public function applyScholarships($accountId, $scholarshipIds, $applicationStatusId);
	public function undoApplyScholarships($accountId, $scholarshipIds);
	public function submitScholarships($accountId, $scholarshipIds);


	// Essays Functions
	public function getEssays($accountId, $essayIds = array());
	public function getEssaysSaved($essayIds);
    public function getApplicationEssayText($accountId, $essayId);
    public function setApplicationEssayText($accountId, $essayId, $text);
}
