<?php

/**
 * JsonModel
 *
 * @package     ScholarshipOwl\Http
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	12. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Http;


class JsonModel extends AbstractModel {
	const STATUS_OK = "ok";
	const STATUS_ERROR = "error";
	const STATUS_REDIRECT = "redirect";
    const STATUS_REDIRECT_POPUP = 'redirect_popup';
	
	private $status;
	private $message;
	
	
	public function __construct() {
		$this->status = self::STATUS_OK;
		$this->message = "";
		
		parent::__construct();
	}
	
	public function send($email = false) {
		$result = array(
			"status" => $this->getStatus(),
			"data" => $this->getData(),
			"message" => $this->getMessage()
		);

        $resultJson = \Response::json($result);
		if($email){
            $resultJson = \Response::json($result, 200, [], JSON_FORCE_OBJECT);
        }
		return $resultJson;
	}
	
	public function getStatus() {
		return $this->status;
	}
	
	public function setStatus($status) {
		$this->status = $status;
	}
	
	public function getMessage() {
		return $this->message;
	}
	
	public function setMessage($message) {
		$this->message = $message;
	}
}
