<?php

/**
 * IAccountService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Account\Profile;


interface IAccountService {
	public function getAccount($accountId);
	public function getAccountEmailById($accountId);
	
	public function getAccountEmailsByIds($accountIds);
	
	public function registerAccount(Account $account, Profile $profile, $checkEmail = true);
	public function generatePassword($length = 9, $add_dashes = false, $available_sets = 'luds');
	public function generateReferralCode($username);
	
	public function changeEmailAndPassword($accountId, $email, $password = "");
	public function changePassword($accountId, $password);
	
	public function getAccountSettings($accountId);
	public function setAccountSettings(Account $account);
	public function getAccountData($accountIds);
	public function getLatestAccounts($count = 5);
}
