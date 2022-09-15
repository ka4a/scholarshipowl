<?php

/**
 * Zu Usa Program Class
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	18. July 2016.
 * @copyright  	ScholarshipOwl
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class ZuUsaProgram extends AbstractEntity {
	private $zuUsaProgramId;
	private $zuUsaCampaignId;
	private $submissionValue;
	private $displayValue;
	private $campus;
	private $zip;
	private $lastDegreeCompleted;
	private $rnLicence;
	private $enrollPercentage;
	private $howDedicated;
	private $state;
	private $computerAccess;


	public function __construct() {
		$this->zuUsaProgramId = 0;
		$this->zuUsaCampaignId = 0;
		$this->submissionValue = "";
		$this->displayValue = "";
		$this->campus = "";
		$this->zip = "";
		$this->lastDegreeCompleted = "";
		$this->rnLicence = "";
		$this->enrollPercentage = "";
		$this->howDedicated = "";
		$this->state = "";
		$this->computerAccess = "";
	}

	public function getZuUsaProgramId(){
		return $this->zuUsaProgramId;
	}

	public function setZuUsaProgramId($zuUsaProgramId){
		$this->zuUsaProgramId = $zuUsaProgramId;
	}

	public function getZuUsaCampaignId(){
		return $this->zuUsaCampaignId;
	}

	public function setZuUsaCampaignId($zuUsaCampaignId){
		$this->zuUsaCampaignId = $zuUsaCampaignId;
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

	public function getCampus(){
		return $this->campus;
	}

	public function setCampus($campus){
		$this->campus = $campus;
	}

	public function getLastDegreeCompleted(){
		return $this->lastDegreeCompleted;
	}

	public function setLastDegreeCompleted($lastDegreeCompleted){
		$this->lastDegreeCompleted = $lastDegreeCompleted;
	}

	public function getRnLicence(){
		return $this->rnLicence;
	}

	public function setRnLicence($rnLicence){
		$this->rnLicence = $rnLicence;
	}

	public function getEnrollPercentage(){
		return $this->enrollPercentage;
	}

	public function setEnrollPercentage($enrollPercentage){
		$this->enrollPercentage = $enrollPercentage;
	}

	public function getHowDedicated(){
		return $this->howDedicated;
	}

	public function setHowDedicated($howDedicated){
		$this->howDedicated = $howDedicated;
	}

	public function getState(){
		return $this->state;
	}

	public function setState($state){
		$this->state = $state;
	}

	public function getComputerAccess(){
		return $this->computerAccess;
	}

	public function setComputerAccess($computerAccess){
		$this->computerAccess = $computerAccess;
	}

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "zu_usa_program_id") {
				$this->setZuUsaProgramId($value);
			}
			else if ($key == "zu_usa_campaign_id") {
				$this->setZuUsaCampaignId($value);
			}
			else if ($key == "submission_value") {
				$this->setSubmissionValue($value);
			}
			else if ($key == "display_value") {
				$this->setDisplayValue($value);
			}
			else if ($key == "campus") {
				$this->setCampus($value);
			}
			else if ($key == "zip") {
				$this->setZip($value);
			}
			else if ($key == "last_degree_completed") {
				$this->setLastDegreeCompleted($value);
			}
			else if ($key == "rn_licence") {
				$this->setRnLicence($value);
			}
			else if ($key == "enroll_percentage") {
				$this->setEnrollPercentage($value);
			}
			else if ($key == "how_dedicated") {
				$this->setHowDedicated($value);
			}
			else if ($key == "state") {
				$this->setState($value);
			}
			else if ($key == "computer_access") {
				$this->setComputerAccess($value);
			}
		}
	}

	public function toArray() {
		return array(
			"zu_usa_program_id" => $this->getZuUsaProgramId(),
			"zu_usa_campaign_id" => $this->getZuUsaCampaignId(),
			"submission_value" => $this->getSubmissionValue(),
			"display_value" => $this->getDisplayValue(),
			"campus" => $this->getCampus(),
			"zip" => $this->getZip(),
			"last_degree_completed" => $this->getLastDegreeCompleted(),
			"rn_licence" => $this->getRnLicence(),
			"enroll_percentage" => $this->getEnrollPercentage(),
			"how_dedicated" => $this->getHowDedicated(),
			"state" => $this->getState(),
			"computer_access" => $this->getComputerAccess(),
		);
	}
}
