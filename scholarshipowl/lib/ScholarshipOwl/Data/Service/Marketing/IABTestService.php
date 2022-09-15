<?php

/**
 * IABTestService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	09. June 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Marketing\ABTest;


interface IABTestService {
	// Updates only start_date, end_date & is_active
	public function updateABTest(ABTest $abTest);

	// Updates is_active
	public function activateABTest($abTestId);
	public function deactivateABTest($abTestId);


	// Getting AB Tests
	public function getABTest($abTestId);
	public function getABTests();
	public function getActiveNonExpiredABTests();
	public function getAccountCountByTestGroup($abTestIds, $testGroup);


	// Account functions
	public function saveABTestAccount(ABTest $abTest, \App\Entity\Account $account);
	public function getAccountsTestGroups($accountIds, $nonExpiredActiveOnly = false);
	public function searchAccounts($abTestId, $params = array(), $limit = "");
}
