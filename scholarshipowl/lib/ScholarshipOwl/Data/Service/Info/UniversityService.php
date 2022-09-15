<?php

/**
 * UniversityService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Frank Castillo <frank.castillo@yahoo.com>
 *
 * @created    	23. March 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;

use ScholarshipOwl\Data\Entity\Info\University;
use ScholarshipOwl\Data\Service\AbstractService;


class UniversityService extends AbstractService implements IUniversityService {
	public function getUniversities($limit = "") {
		$result = array(
			"count" => 0,
			"data" => array(),
		);

		if (!empty($limit)) {
			$limit = "LIMIT {$limit}";
		}

		// Count
		$sql = sprintf("SELECT COUNT(*) AS count FROM %s", self::TABLE_UNIVERSITY);
		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$result["count"] = $row->count;
		}

		// Data
		$sql = sprintf("SELECT * FROM %s %s", self::TABLE_UNIVERSITY, $limit);
		$resultSet = $this->query($sql);

		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new University();
			$entity->populate($row);

			$result["data"][$entity->getUniversityId()] = $entity;
		}

		return $result;
	}

    public function getUniversityName($id) {
		$result = '';
        $sql = sprintf("SELECT * FROM %s where college_id = '{$id}' ", self::TABLE_COLLEGE, $id);
        $resultSet = $this->query($sql);
        foreach($resultSet as $row) {
            $row = (array) $row;
            $result = $row['canonical_name'];
        }
        return $result;
    }
}
