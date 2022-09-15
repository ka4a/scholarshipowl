<?php

/**
 * IHighschoolService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Frank Castillo <frank.castillo@yahoo.com>
 *
 * @created    	20. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


interface IHighschoolService {
	public function getHighschools($limit = "");
	public function getHighschoolName($id);
}
