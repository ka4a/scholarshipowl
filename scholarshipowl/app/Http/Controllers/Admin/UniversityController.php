<?php

namespace App\Http\Controllers\Admin;

use ScholarshipOwl\Data\Service\Info\UniversityService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


class UniversityController extends BaseController {

	public function indexAction()
	{
		$model = new ViewModel("admin/universities/index");
		
		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Static Data" => "/admin/static_data",
				"Universities" => "/admin/universities",
			),
			"title" => "Universities",
			"active" => "static_data",
			"universities" => array(),
			"count" => 0,
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/universities",
				"url_params" => array()
			),
			"search" => array(),
		);
		
		try {
			$service = new UniversityService();
			
			$display = 50;
			$pagination = $this->getPagination($display);
			
			$searchResult = $service->getUniversities($pagination["limit"]);
			
			$data["universities"] = $searchResult["data"];
			$data["count"] = $searchResult["count"];
			$data["pagination"]["page"] = $pagination["page"];
			$data["pagination"]["pages"] = ceil($searchResult["count"] / $display);
			$data["pagination"]["url_params"] = $data["search"];
		}
		catch (\Exception $exc) {
			$this->handleException($exc);
		}
		
		$model->setData($data);
		return $model->send();
	}
}
