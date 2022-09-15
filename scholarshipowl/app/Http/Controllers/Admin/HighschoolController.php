<?php

namespace App\Http\Controllers\Admin;

use ScholarshipOwl\Data\Service\Info\HighschoolService;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


class HighschoolController extends BaseController {

	public function indexAction()
	{
		$model = new ViewModel("admin/highschools/index");
		
		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Static Data" => "/admin/static_data",
				"High Schools" => "/admin/highschools",
			),
			"title" => "High Schools",
			"active" => "static_data",
			"highschools" => array(),
			"count" => 0,
			"pagination" => array(
				"page" => 1,
				"pages" => 0,
				"url" => "/admin/highschools",
				"url_params" => array()
			),
			"search" => array(),
		);
		
		try {
			$service = new HighschoolService();
			
			$display = 50;
			$pagination = $this->getPagination($display);
			
			$searchResult = $service->getHighschools($pagination["limit"]);
			
			$data["highschools"] = $searchResult["data"];
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
