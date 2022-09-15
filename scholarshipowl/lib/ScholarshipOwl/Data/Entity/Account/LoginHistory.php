<?php

/** LoginHistory
 *
 * @package     ScholarshipOwl\Data\Entity\Account
 * @version     1.0
 * @author      Branislav Jovanovic <branej@gmail.com>
 *
 * @created    	26. Decembar 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity\Account;

use ScholarshipOwl\Data\Entity\AbstractEntity;
use ScholarshipOwl\Data\Entity\Account\Account;


class LoginHistory extends AbstractEntity {
	const ACTION_LOGIN  = "login";
	const ACTION_LOGOUT = "logout";

	private $loginHistoryId;
	private $account;
	private $action;
	private $actionDate;
	private $ipAddress;

	public function __construct($loginHistoryId = null) {
		$this->loginHistoryId = $loginHistoryId;
		$this->action = "";
		$this->actionDate = "";
		$this->ipAddress = "";
	}
	public function getLoginHistoryId(){
		return $this->loginHistoryId;
	}

	public function setLoginHistoryId($loginHistoryId){
		$this->loginHistoryId = $loginHistoryId;
	}

	public function getAccount(){
		return $this->account;
	}

	public function setAccount(Account $account){
		$this->account = $account;
	}

	public function getAction(){
		return $this->action;
	}

	public function setAction($action){
		$this->action = $action;
	}

	public function getActionDate(){
		return $this->actionDate;
	}

	public function setActionDate($actionDate){
		$this->actionDate = $actionDate;
	}

	public function getIpAddress() {
		return $this->ipAddress;
	}
	public function setIpAddress($ipAddress) {
		$this->ipAddress = $ipAddress;
	}

	public function __toString() {
		return $this->action.': '.$this->actionDate;
	}
	
	public static function getLoginActions() {
		return array(
			self::ACTION_LOGIN => "Login",
			self::ACTION_LOGOUT => "Logout",
		);
	}
	
	public function populate($row) {
		foreach($row as $key => $value) {
			if($key == "login_history_id") {
				$this->setLoginHistoryId($value);
			}
			if($key == "account_id") {
				$this->getAccount()->setAccountId($value);
			}
			else if($key == "action") {
				$this->setAction($value);
			}
			else if($key == "action_date") {
				$this->setActionDate($value);
			}
			else if($key == "ip_address") {
				$this->setIpAddress($value);
			}
		}
	}

	public function toArray() {
		return array(
			"login_history_id" => $this->getLoginHistoryId(),
			"account_id" => $this->getAccount()->getAccountId(),
			"action" => $this->getAction(),
			"action_date" => $this->getActionDate(),
			"ip_address" => $this->getIpAddress(),
		);
	}
}
