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
use ScholarshipOwl\Data\Entity\Cms\Cms;

class PopupCms extends AbstractEntity{
	private $popupCmsId;
	private $popupId;
	private $cmsId;
	private $popup;
	private $cms;

	public function __construct() {
		$this->popupCmsId = 0;
		$this->cmsId = new Cms();
		$this->popup = new Popup();
		$this->popupId = 0;
		$this->cmsId = 0;
	}

	public function getPopupCmsId(){
		return $this->popupCmsId;
	}

	public function setPopupCmsId($popupCmsId){
		$this->popupCmsId = $popupCmsId;
	}

	public function getPopupId(){
		return $this->popupId;
	}

	public function setPopupId($popupId){
		$this->popupId = $popupId;
	}

	public function getCmsId(){
		return $this->cmsId;
	}

	public function setCmsId($cmsId){
		$this->cmsId = $cmsId;
	}

	public function getPopup(){
		return $this->popup;
	}

	public function setPopup(Popup $popup){
		$this->popup = $popup;
	}

	public function getCms(){
		return $this->cms;
	}

	public function setCms(Cms $cms){
		$this->cms = $cms;
	}

	public function populate($row) {
		foreach ($row as $key => $value) {
			if ($key == "popup_cms_id") {
				$this->setPopupCmsId($value);
			}else if ($key == "popup_id") {
				$this->setPopupId($value);
			}
			else if ($key == "cms_id") {
				$this->setCmsId($value);
			}
		}
	}

	public function toArray() {
		return array(
			"popup_cms_id" => $this->getPopupCmsId(),
			"popup_id" => $this->getPopupId(),
			"cms_id" => $this->getCmsId()
		);
	}
}
