<?php

/**
 * Zu Usa Campus Class
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


class ZuUsaCampus extends AbstractEntity {
	private $zuUsaCampusId;
	private $zuUsaCampaignId;
	private $submissionValue;
	private $displayValue;
	private $zip;
	private $isActive;
    private $monthlyCap;


	public function __construct() {
		$this->zuUsaCampusId = 0;
		$this->zuUsaCampaignId = 0;
		$this->submissionValue = "";
		$this->displayValue = "";
		$this->zip = "";
		$this->isActive = true;
		$this->monthlyCap = 0;
	}

	public function getZuUsaCampusId(){
		return $this->zuUsaCampusId;
	}

	public function setZuUsaCampusId($zuUsaCampusId){
		$this->zuUsaCampusId = $zuUsaCampusId;
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

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function getMonthlyCap(){
        return $this->monthlyCap;
    }

    public function setMonthlyCap($monthlyCap){
        $this->monthlyCap = $monthlyCap;
    }

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "zu_usa_campus_id") {
				$this->setZuUsaCampusId($value);
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
			else if ($key == "zip") {
				$this->setZip($value);
			}
			else if ($key == "is_active") {
				$this->setIsActive($value);
			}
			else if ($key == "monthly_cap") {
				$this->setMonthlyCap($value);
			}
		}
	}

	public function toArray() {
		return array(
			"zu_usa_campus_id" => $this->getZuUsaCampusId(),
			"zu_usa_campaign_id" => $this->getZuUsaCampaignId(),
			"submission_value" => $this->getSubmissionValue(),
			"display_value" => $this->getDisplayValue(),
			"zip" => $this->getZip(),
			"is_active" => $this->getIsActive(),
			"monthly_cap" => $this->getMonthlyCap(),
		);
	}
}
