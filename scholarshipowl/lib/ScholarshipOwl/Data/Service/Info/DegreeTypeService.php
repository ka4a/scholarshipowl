<?php

/**
 * DegreeTypeService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	28. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


class DegreeTypeService extends AbstractInfoService {
	protected function getTable() {
		return self::TABLE_DEGREE_TYPE;
	}
	
	protected function getKeyColumn() {
		return "degree_type_id";
	}
	
	protected function getValueColumn() {
		return "name";
	}
	
	protected function getEntity() {
		return new \ScholarshipOwl\Data\Entity\Info\DegreeType();
	}
}
