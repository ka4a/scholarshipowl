<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 14/9/2015
 */

namespace ScholarshipOwl\Data\Service\Payment;


use ScholarshipOwl\Data\Entity\Payment\Popup;

interface IPopupService {
    public function getPopups();
	public function getPopup($popupId);
	public function addPopup(Popup $popup);
	public function updatePopup(Popup $popup);
	public function getPopupPages($popupId);
}
