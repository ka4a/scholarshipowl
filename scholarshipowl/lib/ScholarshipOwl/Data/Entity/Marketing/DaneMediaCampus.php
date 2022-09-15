<?php

/**
 * Dane Media Campus Class
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	58. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class DaneMediaCampus extends AbstractEntity {
	private $daneMediaCampusId;
	private $daneMediaCampaignId;
	private $submissionValue;
	private $displayValue;
	private $zip;
	private $startTime;
	private $militaryAffiliationId;


	public function __construct() {
		$this->daneMediaCampusId = 0;
		$this->daneMediaCampaignId = 0;
		$this->submissionValue = "";
		$this->displayValue = "";
		$this->zip = "";
		$this->startTime = "";
		$this->militaryAffiliationId = 0;
	}

	public function getDaneMediaCampusId(){
		return $this->daneMediaCampusId;
	}

	public function setDaneMediaCampusId($daneMediaCampusId){
		$this->daneMediaCampusId = $daneMediaCampusId;
	}

	public function getDaneMediaCampaignId(){
		return $this->daneMediaCampaignId;
	}

	public function setDaneMediaCampaignId($daneMediaCampaignId){
		$this->daneMediaCampaignId = $daneMediaCampaignId;
	}

	public function getSubmissionValue(){
		return $this->submissionValue;
	}

	public function setSubmissionValue($submissionValue){
		$this->submissionValue = $submissionValue;
	}

	public function getDisplayValue(){
		return $this->displayValue;
	}

	public function setDisplayValue($displayValue){
		$this->displayValue = $displayValue;
	}

	public function getZip(){
		return $this->zip;
	}

	public function setZip($zip){
		$this->zip = $zip;
	}

	public function getStartTime(){
		return $this->startTime;
	}

	public function setStartTime($startTime){
		$this->startTime = $startTime;
	}

	public function getMilitaryAffiliationId(){
		return $this->militaryAffiliationId;
	}

	public function setMilitaryAffiliationId($militaryAffiliationId){
		$this->militaryAffiliationId = $militaryAffiliationId;
	}

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "dane_media_campus_id") {
				$this->setDaneMediaCampusId($value);
			}
			else if ($key == "dane_media_campaign_id") {
				$this->setDaneMediaCampaignId($value);
			}
			else if ($key == "submission_value") {
				$this->setSubmissionValue($value);
			}
			else if ($key == "display_value") {
				$this->setDisplayValue($value);
			}
			else if ($key == "zip") {
				$this->setZip($value);
			}
			else if ($key == "start_time") {
				$this->setStartTime($value);
			}
			else if ($key == "military_affiliation_id") {
				$this->setMilitaryAffiliationId($value);
			}
		}
	}

	public function toArray() {
		return array(
			"dane_media_campus_id" => $this->getDaneMediaCampusId(),
			"dane_media_campaign_id" => $this->getDaneMediaCampaignId(),
			"submission_value" => $this->getSubmissionValue(),
			"display_value" => $this->getDisplayValue(),
			"zip" => $this->getZip(),
			"start_time" => $this->getStartTime(),
			"military_affiliation_id" => $this->getMilitaryAffiliationId(),
		);
	}
}
