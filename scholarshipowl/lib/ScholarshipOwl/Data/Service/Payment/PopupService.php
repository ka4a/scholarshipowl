<?php

/**
 * ApplicationService
 *
 * @package     ScholarshipOwl\Data\Service\Payment
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	24. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Payment;

use ScholarshipOwl\Data\Entity\Payment\Popup;
use ScholarshipOwl\Data\Entity\Payment\PopupCms;
use ScholarshipOwl\Data\Service\AbstractService;
use ScholarshipOwl\Data\Service\Marketing\RedirectRulesService;


class PopupService extends AbstractService implements IPopupService {

    const CACHE_TAGS = ['popup'];
    const CACHE_KEY_POPUP_BY_PAGE = 'popup.page.%s';

    public function getPopups() {
        $result = array();

        $sql = sprintf("
			SELECT
				p.*
			FROM %s AS p
			ORDER BY p.popup_id DESC
		", self::TABLE_POPUP);

        $resultSet = $this->query($sql, array());
        foreach($resultSet as $row) {
            $row = (array) $row;

            $entity = new Popup();
            $entity->populate($row);

            $result[$entity->getPopupId()] = $entity;
        }

        return $result;
    }

	public function getPopup($popupId) {
		return $this->getEntityByColumn("\\ScholarshipOwl\\Data\\Entity\\Payment\\Popup", self::TABLE_POPUP, "popup_id", $popupId);
	}

	public function addPopup(Popup $popup, $popupCms = array()) {
		return $this->savePopup($popup, $popupCms, true);
	}

	public function updatePopup(Popup $popup, $popupCms = array()) {
		return $this->savePopup($popup, $popupCms, false);
	}

	/**
	 * Deletes Popup
	 *
	 * @param $popupId int
	 * @access public
	 * @return void
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function deletePopup($popupId) {
		try {
			$this->beginTransaction();

			$this->execute(sprintf("DELETE FROM %s WHERE popup_id = ?", self::TABLE_POPUP_CMS), array($popupId));
			$this->delete(self::TABLE_POPUP, array("popup_id" => $popupId));

			$this->commit();
		}
		catch(\Exception $exc) {
			$this->rollback();
			throw $exc;
		}
	}

	public function deletePopupCms($popupId) {
		return $this->execute(sprintf("DELETE FROM %s WHERE popup_id = ?", self::TABLE_POPUP_CMS), array($popupId));
	}

	private function savePopup(Popup $popup, $popupCms = array(), $insert = true) {
		$result = 0;

		$popupId = $popup->getPopupId();
		$data = $popup->toArray();

		unset($data["popup_id"]);

		if($insert == true) {
			$this->insert(self::TABLE_POPUP, $data);
			$popupId = $this->getLastInsertId();

			$result = $popupId;
		}
		else {
			unset($data["popup_id"]);
			$result = $this->update(self::TABLE_POPUP, $data, array("popup_id" => $popupId));
		}

		$this->deletePopupCms($popupId);

		foreach($popupCms as $popupCmsPage){
			$data = $popupCmsPage->toArray();
			$data["popup_id"] = $popupId;
			$popupCmsId = $data["popup_cms_id"];
			unset($data["popup_cms_id"]);

			if (empty($popupCmsId)) {
				$this->insert(self::TABLE_POPUP_CMS, $data);
			}
			else {
				$this->update(self::TABLE_POPUP_CMS, $data, array("popup_cms_id" => $popupCmsId));
			}
		}


		return $result;
	}

	public function getPopupPages($popupId) {
		$result = array();

		$sql = sprintf("
			SELECT pc.*
			FROM popup p
			JOIN popup_cms pc ON pc.popup_id = p.popup_id
			WHERE p.popup_id = ?
		", self::TABLE_POPUP, self::TABLE_POPUP_CMS, self::TABLE_CMS);

		$resultSet = $this->query($sql, array($popupId));
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new PopupCms();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}
}
