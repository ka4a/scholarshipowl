<?php

/**
 * ISearchService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	17. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;


interface ISearchService {
	public function searchScholarships($params = array(), $limit = "", $allColumns = false);
	public function searchApplications($params = array(), $limit = "");
}
