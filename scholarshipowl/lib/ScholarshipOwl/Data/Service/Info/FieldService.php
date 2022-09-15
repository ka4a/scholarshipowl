<?php

/**
 * FieldService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	23. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


class FieldService extends AbstractInfoService {
	protected function getTable() {
		return self::TABLE_FIELD;
	}
	
	protected function getKeyColumn() {
		return "field_id";
	}
	
	protected function getValueColumn() {
		return "name";
	}
	
	protected function getEntity() {
		return new \ScholarshipOwl\Data\Entity\Info\Field();
	}
}
