<?php

/**
 * AbstractController
 *
 * @package     ScholarshipOwl\Http
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	11. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Http;

use App\Entity\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

abstract class AbstractController extends Controller {
	const FLASH_DATA_SESSION_KEY = "FLASH_DATA";

    /**
     * @var \ScholarshipOwl\Data\Entity\Account\Account
     */
    private $user;

    /**
     * @var Account
     */
    protected $account;

	/**
	 * Gets logged user. If not logged returns null
	 *
	 * @access protected
	 * @return null|\ScholarshipOwl\Data\Entity\Account\Account
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getLoggedUser() {
        if ($this->user === null) {

            /** @var Account $user */
            if($user = \Auth::user()) {
                if ($user =  \EntityManager::getRepository(\App\Entity\Account::class)
                    ->findOneBy(['accountId' => $user->getAccountId()])) {
                    $this->user = $user;
                }
            }
        }

		return $this->user;
	}

	/**
	 * Checks if user is logged
	 *
	 * @access protected
	 * @return mixed
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function isLoggedUser() {
		$user = $this->getLoggedUser();
		return isset($user);
	}


	/**
	 * Gets query param
	 *
	 * @access protected
	 * @return mixed
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getQueryParam($name, $default = null) {
		$result = $default;

		if(\Input::has($name)) {
			$result = \Input::get($name);
		}

		return $result;
	}


	/**
	 * Gets all input
	 *
	 * @access protected
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getAllInput() {
		return \Input::all();
	}


	/**
	 * Gets session value
	 *
	 * @access protected
	 * @param string @key
	 * @param string @default
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getSession($key, $default = null) {
		return \Session::get($key, $default);
	}


	/**
	 * Sets session value
	 *
	 * @access protected
	 * @param string @key
	 * @param mixed @value
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function setSession($key, $value) {
		return \Session::put($key, $value);
	}


	/**
	 * Gets flash data
	 *
	 * @access protected
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getFlashData() {
		$result = \Session::get(self::FLASH_DATA_SESSION_KEY);
		\Session::forget(self::FLASH_DATA_SESSION_KEY);

		return $result;
	}


	/**
	 * Sets flash data
	 *
	 * @access protected
	 * @param string @data
	 * @param string @type
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function setFlashData($data, $type) {
		$value = array(
			"data" => $data,
			"type" => $type,
		);

		return \Session::put(self::FLASH_DATA_SESSION_KEY, $value);
	}


	/**
	 * Gets all session
	 *
	 * @access protected
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getAllSession() {
		return \Session::all();
	}


	/**
	 * Gets config value
	 *
	 * @access protected
	 * @return mixed
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getConfig($key) {
		return \Config::get($key);
	}


	/**
	 * Renders view as response
	 *
	 * @access protected
	 * @param string $view
	 * @param array $params
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function render($view, $params = array()) {
		return \View::make($view, $params);
	}


	/**
	 * Sends raw response
	 *
	 * @access protected
	 * @param string $content
	 * @param array $headers
	 * @param int $status
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function response($content, $headers = array(), $status = 200) {
		$response = \Response::make($content, $status);

		foreach($headers as $key => $value) {
			$response->header($key, $value);
		}

		return $response;
	}


	/**
	 * Redirection to URL
	 *
	 * @access protected
	 * @param string $url
	 * @return RedirectResponse
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function redirect($url) {
		\Session::reflash();
		return \Redirect::to($url);
	}


	/**
	 * Redirection to custom route
	 *
	 * @access protected
	 * @param string $route
	 * @param array $params
	 * @return Response
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function redirectToRoute($route, $params = array()) {
		return \Redirect::route($route, $params);
	}


	/**
	 * Log info
	 *
	 * @access protected
	 * @param mixed $data
	 * @return void
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function logInfo($data) {
		return \Log::info($data);
	}


	/**
	 * Log warning
	 *
	 * @access protected
	 * @param mixed $data
	 * @return void
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function logWarning($data) {
		return \Log::warning($data);
	}


	/**
	 * Log error
	 *
	 * @access protected
	 * @param mixed $data
	 * @return void
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function logError($data) {
		return \Log::error($data);
	}


	/**
	 * Gets Pagination Limit And Current Page
	 *
	 * @access protected
	 * @param int $display
	 * @return string
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	protected function getPagination($display = 20) {
		$result = array(
			"page" => 1,
			"limit" => ""
		);

        $page = $this->getQueryParam("page");
		if(empty($page)) {
			$page = 1;
		}

		$result["page"] = $page;
		$result["limit"] = sprintf("%d, %d", ($page - 1) * $display, $display);

		return $result;
	}

    /**
     * @param int $perPage
     */
	protected function paginator($perPage = 50)
    {
        return new \App\Http\Misc\Paginator($perPage);
    }
}
