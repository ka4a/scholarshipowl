<?php

/**
 * IScholarshipService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	29. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;

use ScholarshipOwl\Data\Entity\Scholarship\Form;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;


interface IScholarshipService {
	// Getting Scholarship Functions
	public function getScholarships();
	public function getScholarship($scholarshipId);
	public function getActiveAutomaticScholarship();
	public function getScholarshipEligibilities($scholarshipId);
	public function getScholarshipsData($scholarshipIds, $onlyActive = true, $expiredFromDate = null);


	// Editing Scholarship Functions
	public function saveScholarshipInformation(Scholarship $scholarship);
	public function saveScholarshipEmailApplication(Scholarship $scholarship);
	public function saveScholarshipOnlineApplication(Scholarship $scholarship);
	public function saveScholarshipEssays($scholarshipId, $essays);
	public function saveScholarshipEligibilities($scholarshipId, $eligibilities);
	public function disableScholarships($scholarshipIds);


	// Editing Scholarship Form Functions
	public function getForm($scholarshipId);
	public function setFormField(Form $form);
	public function getFormField($scholarshipId, $formField);
	public function deleteFormField($scholarshipId, $formField);


	// Getting Scholarship By Properties
	public function getFreeScholarships($accountId = null);
	public function getPaidScholarships($accountId = null);
	public function getExpiredScholarships();
	public function checkScholarshipsExpiration($scholarshipIds, $daysInterval);
	public function checkAllScholarshipsExpiration($daysInterval);
}
