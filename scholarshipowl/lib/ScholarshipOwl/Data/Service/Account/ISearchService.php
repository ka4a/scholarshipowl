<?php

/**
 * ISearchService
 *
 * @package     ScholarshipOwl\Data\Service\Account
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	21. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Account;


interface ISearchService {
	public function searchAccounts($params = array(), $limit = "");
}
