<?php

/**
 * ISettingService
 *
 * @package     ScholarshipOwl\Data\Service\Website
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	23. February 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Website;


interface ISettingService {
	public function getSettings();
	
	public function getSetting($name);
	public function setSetting($name, $value, $type, $isAvailableInRest);
}
