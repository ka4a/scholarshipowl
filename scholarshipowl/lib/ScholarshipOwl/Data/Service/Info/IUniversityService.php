<?php

/**
 * IUniversityService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Frank Castillo <frank.castillo@yahoo.com>
 *
 * @created    	20. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


interface IUniversityService {
	public function getUniversities($limit = "");
}
