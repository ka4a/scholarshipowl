<?php

/**
 * InfoServiceFactory
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	31. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


class InfoServiceFactory {
	const INFOSERVICE_NAMESPACE = "\ScholarshipOwl\Data\Service\Info";
	
	
	public static function get($name) {
		$class = sprintf("%s\%s%s", self::INFOSERVICE_NAMESPACE, $name, "Service");
		return new $class();	
	}
	
	public static function getArrayData($name, $options = null) {
		$result = array();
		
		$class = self::get($name);
		$result = $class->getAll(false);
		
		if(isset($options)) {
			$result = $options + $result;
		}
		
		return $result;
	}
}
