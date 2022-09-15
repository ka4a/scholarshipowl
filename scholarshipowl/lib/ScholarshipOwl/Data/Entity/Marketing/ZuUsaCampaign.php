<?php

/**
 * Zu Usa Campaign Class
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


class ZuUsaCampaign extends AbstractEntity {
	private $zuUsaCampaignId;
	private $name;
	private $dailyCap;
	private $monthlyCap;
	private $active;
	private $submissionUrl;
	private $submissionValue;


	public function __construct() {
		$this->zuUsaCampaignId = 0;
		$this->name = "";
		$this->dailyCap = 0;
		$this->monthlyCap = 0;
		$this->active = false;
		$this->submissionUrl = "";
	}

	public function getZuUsaCampaignId(){
		return $this->zuUsaCampaignId;
	}

	public function setZuUsaCampaignId($zuUsaCampaignId){
		$this->zuUsaCampaignId = $zuUsaCampaignId;
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

    public function getSubmissionUrl(){
        return $this->submissionUrl;
    }

    public function setSubmissionUrl($submissionUrl){
        $this->submissionUrl = $submissionUrl;
    }

    public function getSubmissionValue(){
        return $this->submissionValue;
    }

    public function setSubmissionValue($submissionValue){
        $this->submissionValue = $submissionValue;
    }

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "zu_usa_campaign_id") {
				$this->setZuUsaCampaignId($value);
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
			else if ($key == "submission_url") {
				$this->setSubmissionUrl($value);
			}
			else if ($key == "submission_value") {
				$this->setSubmissionValue($value);
			}
		}
	}

	public function toArray() {
		return array(
			"zu_usa_campaign_id" => $this->getZuUsaCampaignId(),
			"name" => $this->getName(),
			"daily_cap" => $this->getDailyCap(),
			"monthly_cap" => $this->getMonthlyCap(),
			"active" => $this->isActive(),
			"submission_url" => $this->getSubmissionUrl(),
			"submission_value" => $this->getSubmissionValue(),
		);
	}
}
