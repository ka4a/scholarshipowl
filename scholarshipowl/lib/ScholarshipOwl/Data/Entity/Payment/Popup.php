<?php

/**
 * Package
 *
 * @package     ScholarshipOwl\Data\Entity\Payment
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	25. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Payment;

use ScholarshipOwl\Data\Entity\AbstractEntity;


class Popup extends AbstractEntity {
	const POPUP_TYPE_RAF = "raf";
    const POPUP_TYPE_POPUP = 'popup';
	const POPUP_TYPE_MISSION = "mission";
	const POPUP_TYPE_PACKAGE= "package";

	const POPUP_DISPLAY_NONE = 0;
	const POPUP_DISPLAY_BEFORE = 1;
	const POPUP_DISPLAY_AFTER = 2;
	const POPUP_DISPLAY_BOTH = 3;
	const POPUP_DISPLAY_EXIT = 4;

	private $popupId;
	private $popupDisplay;
	private $popupTitle;
	private $popupText;
	private $popupType;
	private $popupTargetId;
	private $popupDelay;
	private $popupDisplayTimes;
	private $startDate;
	private $endDate;
	private $triggerUpgrade;
	private $popupExitDialogueText;
    private $rule_set_id;
    private $priority;

	private $popupCms;

	public function __construct() {
		$this->popupId = null;
        $this->popupDisplay = 0;
		$this->popupTitle = "";
		$this->popupText = "";
		$this->popupType = "raf";
		$this->popupTargetId = 0;
		$this->popupDelay = 0;
		$this->popupDisplayTimes = 0;
		$this->startDate = "0000-00-00 00:00:00";
		$this->endDate = "0000-00-00 00:00:00";
		$this->triggerUpgrade = false;
		$this->popupExitDialogueText = "";
        $this->priority = 0;
		$this->popupCms = array();
	}

	public function getPopupId(){
		return $this->popupId;
	}

	public function setPopupId($popupId){
		$this->popupId = $popupId;
	}

	public function getPopupDisplay(){
		return $this->popupDisplay;
	}

	public function setPopupDisplay($popupDisplay){
		$this->popupDisplay = $popupDisplay;
	}

	public function getPopupTitle(){
		return $this->popupTitle;
	}

	public function setPopupTitle($popupTitle){
		$this->popupTitle = $popupTitle;
	}

	public function getPopupText(){
		return $this->popupText;
	}

	public function setPopupText($popupText){
		$this->popupText = $popupText;
	}

	public function getPopupType(){
		return $this->popupType;
	}

	public function setPopupType($popupType){
		$this->popupType = $popupType;
	}

	public function getPopupTargetId(){
		return $this->popupTargetId;
	}

	public function setPopupTargetId($popupTargetId){
		$this->popupTargetId = $popupTargetId;
	}

	public function getPopupDelay(){
	return $this->popupDelay;
}

	public function setPopupDelay($popupDelay){
		$this->popupDelay = $popupDelay;
	}

	public function getDisplayPosition()
    {
        return $this->getPopupDisplayTypes()[$this->popupDisplay];
    }

	public function getPopupDisplayTimes(){
		return $this->popupDisplayTimes;
	}

	public function setPopupDisplayTimes($popupDisplayTimes){
		$this->popupDisplayTimes = $popupDisplayTimes;
	}

	public function getStartDate(){
		return $this->startDate;
	}

	public function setStartDate($startDate){
		$this->startDate = $startDate;
	}

    public function getEndDate(){
        return $this->endDate;
    }

    public function setEndDate($endDate){
        $this->endDate = $endDate;
    }

	public function isTriggerUpgrade(){
		return $this->triggerUpgrade;
	}

	public function setTriggerUpgrade($triggerUpgrade){
		$this->triggerUpgrade = $triggerUpgrade;
	}

	public function addPopupCms(PopupCms $popupCms) {
		$this->popupCms[$popupCms->getPopupCmsId()] = $popupCms;
	}

	public function getPopupExitDialogueText(){
		return $this->popupExitDialogueText;
	}

	public function setPopupExitDialogueText($popupExitDialogueText){
		$this->popupExitDialogueText = $popupExitDialogueText;
	}

	public function getPopupCms() {
		return $this->popupCms;
	}

	public function setPopupCms($popupCmss) {
		foreach ($popupCmss as $popupCms) {
			$this->addPopupCms($popupCms);
		}
	}

    public function setRuleSetId($rule_set_id)
    {
        $this->rule_set_id = $rule_set_id;
    }

    public function getRuleSetId()
    {
        return $this->rule_set_id;
    }

    public static function getPopupTypes() {
		return array(
			self::POPUP_TYPE_RAF => "RAF",
            self::POPUP_TYPE_POPUP => "Popup",
			self::POPUP_TYPE_PACKAGE => "Package",
			self::POPUP_TYPE_MISSION => "Mission"
		);
	}

	public static function getPopupDisplayTypes() {
		return array(
			self::POPUP_DISPLAY_NONE => "None",
			self::POPUP_DISPLAY_BEFORE => "Before",
			self::POPUP_DISPLAY_AFTER => "After",
			self::POPUP_DISPLAY_BOTH => "Both",
			self::POPUP_DISPLAY_EXIT => "Exit"
		);
	}

	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "popup_id") {
				$this->setPopupId($value);
			}
			else if($key == "popup_display") {
				$this->setPopupDisplay($value);
			}
			else if($key == "popup_title") {
				$this->setPopupTitle($value);
			}
			else if($key == "popup_text") {
				$this->setPopupText($value);
			}
			else if($key == "popup_type") {
				$this->setPopupType($value);
			}
			else if($key == "popup_target_id") {
				$this->setPopupTargetId($value);
			}
			else if($key == "popup_delay") {
				$this->setPopupDelay($value);
			}
			else if($key == "popup_display_times") {
				$this->setPopupDisplayTimes($value);
			}
			else if($key == "start_date") {
				$this->setStartDate($value);
			}
			else if($key == "end_date") {
				$this->setEndDate($value);
			}
			else if($key == "trigger_upgrade") {
				$this->setTriggerUpgrade($value);
			}
            else if($key == "popup_exit_dialogue_text") {
                $this->setPopupExitDialogueText($value);
            }
			else if($key == "rule_set_id") {
				$this->setRuleSetId($value);
			}
			else if($key == "priority") {
				$this->setPriority($value);
			}
		}
	}

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     * @return $this
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;
        return $this;
    }

	public function toArray() {
		return array(
			"popup_id" => $this->getPopupId(),
			"popup_display" => $this->getPopupDisplay(),
			"popup_title" => $this->getPopupTitle(),
			"popup_text" => $this->getPopupText(),
			"popup_type" => $this->getPopupType(),
			"popup_target_id" => $this->getPopupTargetId(),
			"popup_delay" => $this->getPopupDelay(),
			"popup_display_times" => $this->getPopupDisplayTimes(),
			"start_date" => $this->getStartDate(),
			"end_date" => $this->getEndDate(),
			"trigger_upgrade" => $this->isTriggerUpgrade(),
			"popup_exit_dialogue_text" => $this->getPopupExitDialogueText(),
			"rule_set_id" => $this->getRuleSetId(),
			"priority" => $this->getPriority(),
		);
	}
}
