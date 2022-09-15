<?php

/**
 * HighschoolService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Frank Castillo <frank.castillo@yahoo.com>
 *
 * @created    	23. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;

use ScholarshipOwl\Data\Entity\Info\Highschool;
use ScholarshipOwl\Data\Service\AbstractService;


class HighschoolService extends AbstractService implements IHighschoolService {
	public function getHighschools($limit = "") {
		$result = array(
			"count" => 0,
			"data" => array(),
		);

		if (!empty($limit)) {
			$limit = "LIMIT {$limit}";
		}

		// Count
		$sql = sprintf("SELECT COUNT(*) AS count FROM %s", self::TABLE_HIGHSCHOOL);
		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$result["count"] = $row->count;
		}

		// Data
		$sql = sprintf("SELECT * FROM %s %s", self::TABLE_HIGHSCHOOL, $limit);
		$resultSet = $this->query($sql);

		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Highschool();
			$entity->populate($row);

			$result["data"][$entity->getHighschoolId()] = $entity;
		}

		return $result;
	}

    public function getHighschoolName($id) {
		$result = '';
        $sql = sprintf("SELECT * FROM %s where highschool_id = '{$id}' ", self::TABLE_HIGHSCHOOL, $id);
        $resultSet = $this->query($sql);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result = $row['name'];
        }
        return $result;
    }

}
