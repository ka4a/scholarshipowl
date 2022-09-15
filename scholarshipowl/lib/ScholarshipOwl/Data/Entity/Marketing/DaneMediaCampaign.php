<?php

/**
 * Dane Media Campaign Class
 *
 * @package     ScholarshipOwl\Data\Entity\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	18. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Marketing;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class DaneMediaCampaign extends AbstractEntity {
	private $daneMediaCampaignId;
	private $name;
	private $dailyCap;
	private $monthlyCap;
	private $active;
	private $submissionValue;


	public function __construct() {
		$this->daneMediaCampaignId = 0;
		$this->name = "";
		$this->dailyCap = 0;
		$this->monthlyCap = 0;
		$this->active = false;
		$this->submissionValue = "";
	}

	public function getDaneMediaCampaignId(){
		return $this->daneMediaCampaignId;
	}

	public function setDaneMediaCampaignId($daneMediaCampaignId){
		$this->daneMediaCampaignId = $daneMediaCampaignId;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
	}

	public function getDailyCap(){
		return $this->dailyCap;
	}

	public function setDailyCap($dailyCap){
		$this->dailyCap = $dailyCap;
	}

	public function getMonthlyCap(){
		return $this->monthlyCap;
	}

	public function setMonthlyCap($monthlyCap){
		$this->monthlyCap = $monthlyCap;
	}

	public function isActive(){
		return $this->active;
	}

	public function setActive($active){
		$this->active = $active;
	}

    public function getSubmissionValue(){
        return $this->submissionValue;
    }

    public function setSubmissionValue($submissionValue){
        $this->submissionValue = $submissionValue;
    }

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "dane_media_campaign_id") {
				$this->setDaneMediaCampaignId($value);
			}
			else if ($key == "name") {
				$this->setName($value);
			}
			else if ($key == "daily_cap") {
				$this->setDailyCap($value);
			}
			else if ($key == "monthly_cap") {
				$this->setMonthlyCap($value);
			}
			else if ($key == "active") {
				$this->setActive($value);
			}
			else if ($key == "submission_value") {
				$this->setSubmissionValue($value);
			}
		}
	}

	public function toArray() {
		return array(
			"dane_media_campaign_id" => $this->getDaneMediaCampaignId(),
			"name" => $this->getName(),
			"daily_cap" => $this->getDailyCap(),
			"monthly_cap" => $this->getMonthlyCap(),
			"active" => $this->isActive(),
			"submission_value" => $this->getSubmissionValue(),
		);
	}
}
