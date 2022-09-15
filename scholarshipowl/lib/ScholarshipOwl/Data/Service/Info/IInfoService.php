<?php

/**
 * IInfoService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;


interface IInfoService {
	public function getById($id, $asEntity = false);
	public function getAll($asEntity = false);
}
