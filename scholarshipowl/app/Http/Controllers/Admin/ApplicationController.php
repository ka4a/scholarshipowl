<?php

namespace App\Http\Controllers\Admin;

use ScholarshipOwl\Data\Entity\Scholarship\ApplicationStatus;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\Scholarship\ApplicationService;
use ScholarshipOwl\Data\Service\Scholarship\SearchService as ScholarshipSearchService;
use ScholarshipOwl\Http\ViewModel;
use ScholarshipOwl\Http\JsonModel;


/**
 * Application Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class ApplicationController extends BaseController {
	/**
	 * Applications Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/applications/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Applications" => "/admin/applications",
			),
			"title" => "Applications",
			"active" => "applications"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Applications Search Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function searchAction() {
		$model = new ViewModel("admin/applications/search");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Applications" => "/admin/applications",
				"Search Applications" => "/admin/applications/search"
			),
			"title" => "Search Applications",
			"active" => "applications",
			"applications" => array(),
			"count" => 0,
			"search" => array(
                "account_id" => "",
				"first_name" => "",
				"last_name" => "",
				"title" => "",
				"scholarship_id" => "",
				"expiration_date_from" => "",
				"expiration_date_to" => "",
				"date_applied_from" => "",
				"date_applied_to" => "",
				"application_status_id" => "",
				"application_type" => "",
			),
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/applications/search",
				"url_params" => array()
			),
			"options" => array(
				"application_statuses" => ApplicationStatus::getApplicationStatuses(),
				"application_types" => Scholarship::getApplicationTypes(),
			)
		);

		try {
			$service = new ScholarshipSearchService();

			$display = 10;
			$pagination = $this->getPagination($display);

			$input = $this->getAllInput();
			unset($input["page"]);
			foreach($input as $key => $value) {
				$data["search"][$key] = $value;
			}

			$searchResult = $service->searchApplications($data["search"], $pagination["limit"]);

			$data["applications"] = $searchResult["data"];
			$data["count"] = $searchResult["count"];
			$data["pagination"]["page"] = $pagination["page"];
			$data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
			$data["pagination"]["url_params"] = $data["search"];
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}



	/**
	 * Applications View Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function viewAction() {
		$model = new ViewModel("admin/applications/view");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Applications" => "/admin/applications",
				"Search Applications" => "/admin/applications/search",
			),
			"title" => "View Application",
			"active" => "applications",
			"application" => null,
		);

		try {
			$accountId = $this->getQueryParam("account_id");
			$scholarshipId = $this->getQueryParam("scholarship_id");

			if (empty($accountId)) {
				throw new \Exception("Account id not provided");
			}

			if (empty($scholarshipId)) {
				throw new \Exception("Scholarship id not provided");
			}

			$service = new ApplicationService();
			$application = $service->getApplication($accountId, $scholarshipId);

			$data["application"] = $application;
			$data["breadcrumb"]["View Application"] = "/admin/applications/view?account_id=$accountId&scholarship_id=$scholarshipId";
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}
}
