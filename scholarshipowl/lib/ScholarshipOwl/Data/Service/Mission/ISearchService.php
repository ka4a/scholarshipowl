<?php

/**
 * ISearchService
 *
 * @package     ScholarshipOwl\Data\Service\Mission
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	02. July 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Mission;


interface ISearchService {
	public function searchMissionAccount($params = array(), $limit = "");
}
