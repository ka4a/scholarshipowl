<?php

/**
 * AbstractInfoService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;

use ScholarshipOwl\Data\Service\AbstractService;


abstract class AbstractInfoService extends AbstractService implements IInfoService {
	const CACHE_KEY_PREFIX = "INFO";
	
	
	private $data;
	private $dataValues;
	
	abstract protected function getTable();
	abstract protected function getKeyColumn();
	abstract protected function getValueColumn();
	abstract protected function getEntity();
	
	
	public function __construct() {
		$this->data = array();
		$this->dataValues = array();
		
		$this->collect();
	}
	
	protected function collect() {
		$cacheKey = sprintf("%s.%s", self::CACHE_KEY_PREFIX, $this->getTable());
		$data = $this->getFromCache($cacheKey);
		
		if(empty($data)) {
			$this->logInfo(sprintf("%s not found in cache", $cacheKey));
			
			$table = $this->getTable();
			$keyColumn = $this->getKeyColumn();
			$valueColumn = $this->getValueColumn();
			
			$sql = sprintf("SELECT * FROM %s", $table);
			$resultSet = $this->query($sql);

			foreach($resultSet as $row) {
				$entity = $this->getEntity();
				$entity->populate((array) $row);
				
				$this->data[$row->$keyColumn] = $entity;
				$this->dataValues[$row->$keyColumn] = $row->$valueColumn;
			}
			
			$this->setToCache($cacheKey, array("data" => $this->data, "dataValues" => $this->dataValues), 60 * 24);
			$this->logInfo(sprintf("%s saved to cache", $cacheKey));
		}
		else {
			$this->data = $data["data"];
			$this->dataValues = $data["dataValues"];
		}
	}
	
	public function getById($id, $asEntity = false) {
		$result = null;
		$storage = ($asEntity == true) ? $this->data : $this->dataValues;
		
		if(array_key_exists($id, $storage)) {
			$result = $storage[$id];
		}
		
		return $result;
	}
	
	public function getAll($asEntity = false) {
		return ($asEntity == true) ? $this->data : $this->dataValues;
	}
}
