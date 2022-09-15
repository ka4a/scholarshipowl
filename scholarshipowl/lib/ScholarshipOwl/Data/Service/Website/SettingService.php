<?php

/**
 * SettingService
 *
 * @package     ScholarshipOwl\Data\Service\Website
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	23. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Website;

use ScholarshipOwl\Data\Entity\Website\Setting;
use ScholarshipOwl\Data\Service\AbstractService;


class SettingService extends AbstractService implements ISettingService {
	const CACHE_KEY_PREFIX = "SETTINGS";


	public function getSettings() {
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_SETTING);
		$resultSet = $this->query($sql);

		foreach ($resultSet as $row) {
			$row = (array) $row;
			$row = $this->hydrateRow($row);

			$entity = new Setting();
			$entity->populate($row);

			$result[$entity->getGroup()][] = $entity;
		}

		return $result;
	}

	public function getSetting($name) {
		$result = "";
		$cacheKey = sprintf("%s.%s", self::CACHE_KEY_PREFIX, $name);
		$result = $this->getFromCache($cacheKey);
		if (!isset($result) || $result === false) {
			$this->logInfo(sprintf("%s not found in cache", $cacheKey));
			$sql = sprintf("SELECT value FROM %s WHERE name = ?", self::TABLE_SETTING);
			$resultSet = $this->query($sql, array($name));
			foreach ($resultSet as $row) {
				if(!empty($row->value)){
					eval("\$result = ".$row->value.";");
				}
			}

			$this->setToCache($cacheKey, $this->toJson($result), 7 * 60 * 24);
			$this->logInfo(sprintf("%s saved to cache", $cacheKey));
		}
		else {
			$result = $this->fromJson($result);
		}
		return $result;
	}



	public function setSetting($name, $value, $type, $isAvailableInRest = 0) {
		$result = 0;
		$this->validateType($value, $type);

		$value = $this->toJson($value);

		$sql = sprintf("UPDATE %s SET value = ? , is_available_in_rest = ? WHERE name = ?", self::TABLE_SETTING);
		$result = $this->execute($sql, array($value, $isAvailableInRest, $name));

		$cacheKey = sprintf("%s.%s", self::CACHE_KEY_PREFIX, $name);
		$this->setToCache($cacheKey, $value, 7 * 60 * 24);
		app(\App\Services\SettingService::class)->refresh();
		$this->logInfo(sprintf("%s saved to cache", $cacheKey));

		return $result;
	}

	private function toJson($value) {
		return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}

	private function fromJson($value) {
		return json_decode($value, true);
	}

	private function hydrateRow($row) {
		$keys = array("value", "default_value", "options");

		foreach ($keys as $key) {
			if (array_key_exists($key, $row)) {
				$row[$key] = $this->fromJson($row[$key]);
			}
		}

		return $row;
	}

	private function validateType($value, $type) {
		if ($type == Setting::TYPE_INT) {
			if (!preg_match("/^\d+$/", $value)) {
				throw new SettingValueNotValidException("Setting value not an int");
			}
		}
		else if ($type == Setting::TYPE_DECIMAL) {
			if (!preg_match("^[\d]+(|\.[\d]+)$", $value)) {
				throw new SettingValueNotValidException("Setting value not a decimal");
			}
		}
		else if ($type == Setting::TYPE_STRING) {
			if (!is_string($value)) {
				throw new SettingValueNotValidException("Setting value not a string");
			}
		}
		else if ($type == Setting::TYPE_TEXT) {
			if (!is_string($value)) {
				throw new SettingValueNotValidException("Setting value not a text");
			}
		}
		else if ($type == Setting::TYPE_ARRAY) {
			if (!is_array($value)) {
				throw new SettingValueNotValidException("Setting value not an array");
			}
		}
	}
}
