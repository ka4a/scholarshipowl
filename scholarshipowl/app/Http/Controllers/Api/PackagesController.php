<?php
/**
 * Author: Ivan Krkotic (clone@mail2joe.com)
 * Date: 22/6/2015
 */

namespace App\Http\Controllers\Api;



use Illuminate\Support\Facades\View;
use ScholarshipOwl\Data\Service\Payment\PackageService;

class PackagesController extends BaseController{
    public function getPackageAction($packageId){
		$model = $this->getOkModel("package");
		$data = array();

		try {
			$packageService = new PackageService();

			$data = $packageService->getPackage($packageId);

			$model->setData($data);
		}catch (\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}

	public function getPackageViewAction($packageId){
		$model = $this->getOkModel("package");
		$data = array();

		try {
			$packageService = new PackageService();

			$view = View::make("includes.package", array("package" => $packageService->getPackage($packageId)))->render();

			$model->setData((String)$view);
		}catch (\Exception $exc) {
			$this->handleException($exc);
			$model = $this->getErrorModel(self::ERROR_CODE_SYSTEM_ERROR);
		}

		return $model->send();
	}
}
