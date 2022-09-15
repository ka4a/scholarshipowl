<?php

/**
 * IStatisticService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	17. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;


interface IStatisticService {
	// Applications Statistics
	public function getApplicationsCountByAccountIds($accountIds);
	public function getApplicationsCountByAccountIdsAndStatus($accountIds, $status);
	public function getApplicationsCountByScholarshipIds($scholarshipIds);
}
