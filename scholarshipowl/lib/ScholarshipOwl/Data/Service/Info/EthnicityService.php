<?php

/**
 * EthnicityService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	28. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


class EthnicityService extends AbstractInfoService {
	protected function getTable() {
		return self::TABLE_ETHNICITY;
	}
	
	protected function getKeyColumn() {
		return "ethnicity_id";
	}
	
	protected function getValueColumn() {
		return "name";
	}
	
	protected function getEntity() {
		return new \ScholarshipOwl\Data\Entity\Info\Ethnicity();
	}
}
