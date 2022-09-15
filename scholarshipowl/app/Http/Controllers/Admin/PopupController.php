<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Marketing\RedirectRulesSet;
use ScholarshipOwl\Data\Entity\Payment\Popup;
use ScholarshipOwl\Data\Entity\Payment\PopupCms;
use ScholarshipOwl\Data\Service\Cms\CmsService;
use ScholarshipOwl\Data\Service\Marketing\RedirectRulesService;
use ScholarshipOwl\Data\Service\Mission\MissionService;
use ScholarshipOwl\Data\Service\Payment\PackageService;
use ScholarshipOwl\Data\Service\Payment\PopupService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * Popup Controller for admin
 *
 * @author Ivan Krkotic <ivan@siriomedia.com>
 */

class PopupController extends BaseController {

	/**
	 * Popup Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/popup/index");

        $data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Popup" => "/admin/popup"
			),
			"title" => "Popup",
			"active" => "popup",
		);

        $model->setData($data);
        return $model->send();
	}

    /**
     * Popup Search Action
     *
     * @access public
     * @return Response
     *
     * @author Ivan Krkotic <ivan@siriomedia.com>
     */
    public function searchAction() {
        $model = new ViewModel("admin/popup/search");

        $data = array(
            "user" => $this->getLoggedUser(),
            "breadcrumb" => array(
                "Dashboard" => "/admin/dashboard",
                "Popup" => "/admin/popup",
                "Search Popups" => "/admin/popup/search"
            ),
            "title" => "Search Popups",
            "active" => "popup",
            "popups" => array()
        );

        try {
            $service = new PopupService();
            $data["popups"] = $service->getPopups();
        }
        catch(\Exception $exc) {
            $this->handleException($exc);
        }

        $model->setData($data);
        return $model->send();
    }


	/**
	 * Popup Save Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function saveAction() {
		$model = new ViewModel("admin/popup/save");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Popup" => "/admin/popup",
			),
			"active" => "popup",
			"popup" => new Popup(),
			"options" => array(
				"popup_types" => array("" => "--- Select ---") + Popup::getPopupTypes(),
				"popup_display_types" => array("" => "--- Select ---") + Popup::getPopupDisplayTypes(),
				"trigger_upgrade" => array("" => "--- Select ---", "1" => "Yes", "0" => "No"),
                "redirect_rules_sets" => [null => 'None'] + RedirectRulesSet::options(),
			),
		);

		try {
			$service = new PopupService();
			$cmsService = new CmsService();
            $missionService = new MissionService();
            $packageService = new PackageService();

			$packages = $packageService->getPackages();
			foreach ($packages as $packageId => $package) {
				$data["options"]["packages"][$packageId] = $package->getName();
			}

            $missions = $missionService->getMissions();
            foreach ($missions as $missionId => $mission) {
                $data["options"]["missions"][$missionId] = $mission->getName();
            }

            $pages = $cmsService->getAllCms();
            foreach ($pages as $cmsId => $page) {
                $data["options"]["pages"][$cmsId] = $page->getPage();
            }

			$id = $this->getQueryParam("id");

			$data["options"]["used_pages"] = array();
			$usedPages = $service->getPopupPages($id);
			foreach($usedPages as $usedPage){
				$data["options"]["used_pages"][] = $usedPage->getCmsId();
			}
			if(empty($id)) {
				$data["title"] = "Add Popup";
				$data["breadcrumb"]["Add Popup"] = "/admin/popup/save";
			}else {
				$popup = $service->getPopup($id);

				$data["title"] = $popup->getPopupTitle();
				$data["popup"] = $popup;
				$data["breadcrumb"]["Edit Popup"] = "/admin/popup/save?id=$id";
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Post Save Popup Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function postSaveAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();
			$errors = array();
			$pages = array();


			if ($input["popup_display"] == '') {
				$errors["popup_display"] = "Display is empty!";
			}

			if (empty($input["popup_title"])) {
				$errors["popup_title"] = "Title is empty!";
			}

			if (empty($input["start_date"])) {
				$errors["start_date"] = "Start date is empty !";
			}

			if (empty($input["end_date"])) {
				$errors["end_date"] = "End date is empty !";
			}

			if(empty($input["pages"])){
				$errors["pages"] = "Pages are empty !";
			}
            if (empty($errors)) {
				$service = new PopupService();

				$popup = new Popup();

				foreach ($input["pages"] as $page){
					$popupCms = new PopupCms();
					$popupCms->setCmsId($page);

					if (!empty($input["popup_id"])) {
						$popupCms->setPopupId($input["popup_id"]);
					}

					$pages[] = $popupCms;
				}

				if($input["popup_type"] == Popup::POPUP_TYPE_MISSION) {
					$input["popup_target_id"] = $input["popup_target_id_mission"];
				}else if($input["popup_type"] == Popup::POPUP_TYPE_PACKAGE){
					$input["popup_target_id"] = $input["popup_target_id_package"];
				}

                $input['rule_set_id'] = $input['rule_set_id'] ?: null;

                $popup->populate($input);

				if (empty($input["popup_id"])) {
					$referralAwardId = $service->addPopup($popup, $pages);

					$model->setStatus(JsonModel::STATUS_REDIRECT);
					$model->setMessage("Popup saved !");
					$model->setData("/admin/popup/search");
				}
				else {
					$service->updatePopup($popup, $pages);

					$model->setStatus(JsonModel::STATUS_OK);
					$model->setMessage("Popup saved !");
				}
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage("Please fix errors !");
				$model->setData($errors);
			}
		}
		catch(\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
		}

		return $model->send();
	}

	/**
	 * Delete Popup Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Ivan Krkotic <ivan@siriomedia.com>
	 */
	public function deleteAction(){
		try {
			$service = new PopupService();
			$id = $this->getQueryParam("id");

			$service->deletePopup($id);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $this->redirect("/admin/popup/search");
	}
}
