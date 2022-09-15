<?php

namespace App\Http\Controllers\Admin;

use App\Entity\FeatureSet;
use ScholarshipOwl\Data\Entity\Account\AccountType;
use ScholarshipOwl\Data\Entity\Account\AccountStatus;
use ScholarshipOwl\Data\Entity\Website\Setting;
use ScholarshipOwl\Data\Service\Info\InfoServiceFactory;
use ScholarshipOwl\Data\Service\Website\SettingService;
use ScholarshipOwl\Data\Service\Website\SettingValueNotValidException;
use ScholarshipOwl\Http\JsonModel;
use ScholarshipOwl\Http\ViewModel;


/**
 * Website Controller for admin
 *
 * @author Marko Prelic <markomys@gmail.com>
 */

class WebsiteController extends BaseController {

	/**
	 * Website Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function indexAction() {
		$model = new ViewModel("admin/website/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Website" => "/admin/website"
			),
			"title" => "Website",
			"active" => "website"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Static Data Index Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function staticDataAction() {
		$model = new ViewModel("admin/static_data/index");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Static Data" => "/admin/static_data"
			),
			"title" => "Static Data",
			"active" => "static_data"
		);

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Website Settings Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function settingsAction() {
		$model = new ViewModel("admin/website/settings");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Website" => "/admin/website",
				"General Settings" => "/admin/website/settings"
			),
			"title" => "General Settings",
			"active" => "website",
			"settings" => array(),
		);

		try {
			$settingService = new SettingService();
			$settings = $settingService->getSettings();

			ksort($settings);
			$data["settings"] = $settings;
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Website Save Settings Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function postSettingsAction() {
		$model = new JsonModel();

		try {
			$input = $this->getAllInput();
			$error = "";

			if(empty($input["name"])) {
				$error = "Setting name is empty !";
			}
			else if(empty($input["value"])) {
				$error = "Setting value is empty !";
			}
			else if(empty($input["type"])) {
				$error = "Setting type is empty !";
			}

			if (empty($error)) {
				$settingService = new SettingService();
				$settingService->setSetting($input["name"], $input["value"], $input["type"], $input['isAvailableInRest']);

				$model->setStatus(JsonModel::STATUS_OK);
				$model->setMessage("Setting saved !");
			}
			else {
				$model->setStatus(JsonModel::STATUS_ERROR);
				$model->setMessage($error);
				$model->setData($error);
			}
		}
		catch(SettingValueNotValidException $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage($exc->getMessage());
			$model->setData($error);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);

			$model->setStatus(JsonModel::STATUS_ERROR);
			$model->setMessage("System error !");
			$model->setData($error);
		}

		return $model->send();
	}


	/**
	 * Website Static Data Account Fields Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function accountFieldsAction() {
		$model = new ViewModel("admin/website/static_data");

		$data = array(
			"user" => $this->getLoggedUser(),
			"breadcrumb" => array(
				"Dashboard" => "/admin/dashboard",
				"Static Data" => "/admin/static_data",
				"Account Fields" => "/admin/website/account_fields"
			),
			"title" => "Static Data",
			"active" => "static_data"
		);

		try {
			$classes = array("CareerGoal", "Citizenship", "Country", "Degree", "DegreeType", "Ethnicity", "SchoolLevel", "State", "Field");
			foreach($classes as $class) {
				$infoService = InfoServiceFactory::get($class);
				$data["static_data"][$class] = $infoService->getAll();
			}

			$data["static_data"]["AccountType"] = AccountType::getAccountTypes();
			$data["static_data"]["AccountStatus"] = AccountStatus::getAccountStatuses();
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		$model->setData($data);
		return $model->send();
	}


	/**
	 * Mail Template Preview Action
	 *
	 * @access public
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function mailTemplateAction() {
		$model = new JsonModel();
		$model->setStatus(JsonModel::STATUS_OK);

		try {
			$view = \View::make("mail_template", array("content" => "Content goes here"));
			$content = $view->render();

			$model->setData($content);
		}
		catch(\Exception $exc) {
			$this->handleException($exc);
		}

		return $model->send();
	}

    public function clearCache()
    {
        \Cache::flush();

        return \Redirect::back()->with('message', 'Cache successfully cleared!');
    }

    /**
     * @access public
     *
     * @return \Illuminate\Contracts\View\View
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function commands()
    {
        if (request()->get('event-listener')) {
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache'); // recommended to prevent caching of event data.

            $targetCommand = request()->get('cmd', '');
            $targetCommand = "php artisan {$targetCommand}";

            $accountId = \Auth::user()->getAccountId();
            $cacheCommandKey = "command-runner-command-{$accountId}".md5($targetCommand);;
            $cacheDataKey = "command-runner-result-{$accountId}-".md5($targetCommand);
            $cacheChunkKey = "command-runner-chunk-{$accountId}-".md5($targetCommand);
            $cacheFinishKey = "command-runner-finish-{$accountId}-".md5($targetCommand);

            $sendMsg = function ($id, $msg) {
                echo "id: $id" . PHP_EOL;
                echo $msg;
                echo PHP_EOL;
                @ ob_flush();
                @ flush();
            };

            $prepareMsg = function (array $data, $finishingChunk = false) use ($cacheChunkKey) {
                $lastReturnedChunk = \Cache::get($cacheChunkKey, 0);

                $responseArray = array_slice($data, $lastReturnedChunk);
                $response = '';
                foreach ($responseArray as $v) {
                    $response .= 'data: ' . $v . PHP_EOL;
                }

                if (!$finishingChunk) {
                    $itemsCnt = count($data);
                    $chunkNo = $itemsCnt ? count($data) : 0;
                   \Cache::put($cacheChunkKey, $chunkNo, 60);
                } else {
                    \Cache::delete($cacheChunkKey);
                }

                return $response;
            };

            $prepareStaticMsg = function (array $data) {
                $response = '';
                foreach ($data as $v) {
                    $response .= 'data: ' . $v . PHP_EOL;
                }

                return $response;
            };

            $pullDataArray = function () use ($cacheDataKey) {
                $dataString = \Cache::get($cacheDataKey);
                if ($dataString) {
                    $dataArray = json_decode($dataString);
                } else {
                    $dataArray = [];
                }

                return $dataArray;
            };

            $isCommandRunning = (bool)\Cache::get($cacheCommandKey);
            $isCommandFinished = (bool)\Cache::get($cacheFinishKey);

            if ($isCommandRunning) {
                $dataArray = $pullDataArray();
                $sendMsg(time(), $prepareMsg($dataArray));
            } else if ($isCommandFinished) {
                $dataArray = $pullDataArray();
                $dataArray[] = 'END-OF-STREAM';
                \Cache::delete($cacheDataKey);
                \Cache::delete($cacheFinishKey);
                $sendMsg(time(), $prepareMsg($dataArray, true));
            } else {
                exec("cd ../ && php artisan command:run --onBehalfOfAccount={$accountId} --cmd='{$targetCommand}' > /dev/null 2>&1 &", $result);
                $sendMsg(time(), $prepareStaticMsg(['Command execution started']));
            }

        } else {
            $model = new ViewModel("admin/website/commands");

            $model->setData([
                'user' => $this->getLoggedUser(),
                'breadcrumb' => array(
                    'Dashboard' => '/admin/dashboard',
                    'Website' => '/admin/website',
                    'Commands' => '/admin/website/commands'
                ),
                'title' => 'Commands',
                'active' => 'website',
            ]);

            return $model->send();
        }
    }
}
