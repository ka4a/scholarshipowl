<?php

/**
 * SchoolLevelService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	28. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


class SchoolLevelService extends AbstractInfoService {
	protected function getTable() {
		return self::TABLE_SCHOOL_LEVEL;
	}
	
	protected function getKeyColumn() {
		return "school_level_id";
	}
	
	protected function getValueColumn() {
		return "name";
	}
	
	protected function getEntity() {
		return new \ScholarshipOwl\Data\Entity\Info\SchoolLevel();
	}
}
