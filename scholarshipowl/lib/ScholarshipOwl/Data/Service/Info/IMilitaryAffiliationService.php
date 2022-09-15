<?php

/**
 * IMilitaryAffiliationService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created    	21. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


interface IMilitaryAffiliationService {
	public function getMilitaryAffiliation($id);
	public function getMilitaryAffiliations($limit = "");
	public function getMilitaryAffiliationAutocomplete($term, $limit = 10);
}
