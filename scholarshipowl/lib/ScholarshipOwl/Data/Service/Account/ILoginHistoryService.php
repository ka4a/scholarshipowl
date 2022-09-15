<?php

/**
 * ILoginHistoryService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Branislav Jovanovic <branej@gmail.com>
 *
 * @created    	26. December 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;


interface ILoginHistoryService {
	public function saveLogin($accountId);
	public function saveLogout($accountId);
	
	public function getAccountLoginHistory($accountId, $limit = "");
	public function getLastLoginDate($accountIds);
}
