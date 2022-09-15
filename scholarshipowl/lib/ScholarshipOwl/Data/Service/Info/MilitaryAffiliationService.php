<?php

/**
 * MilitaryAffiliationService
 *
 * @package     ScholarshipOwl\Data\Service\Info
 * @version     1.0
 * @author      Ivan Krkotic <ivan.krkotic@gmail.com>
 *
 * @created    	21. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Info;

use ScholarshipOwl\Data\Entity\Info\MilitaryAffiliation;
use ScholarshipOwl\Data\Service\AbstractService;


class MilitaryAffiliationService extends AbstractInfoService implements IMilitaryAffiliationService {
	public function getMilitaryAffiliation($id) {
		$result = new MilitaryAffiliation();
		if(is_numeric($id)) {
			$sql = sprintf("SELECT * FROM %s where military_affiliation_id = %s ", self::TABLE_MILITARY_AFFILIATION, $id);
			$resultSet = $this->query($sql);

			foreach ($resultSet as $row) {
				$row = (array)$row;

				$result->populate($row);
			}
		}
		return $result;
	}

	public function getMilitaryAffiliations($limit = "") {
		$result = array(
			"count" => 0,
			"data" => array(),
		);

		if (!empty($limit)) {
			$limit = "LIMIT {$limit}";
		}

		// Count
		$sql = sprintf("SELECT COUNT(*) AS count FROM %s", self::TABLE_MILITARY_AFFILIATION);
		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$result["count"] = $row->count;
		}

		// Data
		$sql = sprintf("SELECT * FROM %s %s ORDER BY military_affiliation_id", self::TABLE_MILITARY_AFFILIATION, $limit);
		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new MilitaryAffiliation();
			$entity->populate($row);

			$result["data"][$entity->getMilitaryAffiliationId()] = $entity;
		}



		return $result;
	}


    public function getMilitaryAffiliationAutocomplete($term, $limit = 10) {
        $result = [];
        // Data

        $sql = sprintf("SELECT * FROM %s where name like '%s' limit %s", self::TABLE_MILITARY_AFFILIATION, "%".addslashes($term)."%", $limit);

        $resultSet = $this->query($sql);

        foreach($resultSet as $row) {
            $row = (array) $row;
            $result[$row['military_affiliation_id']] = $row['name'];
        }

        return $result;
    }

	protected function getTable() {
		return self::TABLE_MILITARY_AFFILIATION;
	}

	protected function getKeyColumn() {
		return "military_affiliation_id";
	}

	protected function getValueColumn() {
		return "name";
	}

	protected function getEntity() {
		return new \ScholarshipOwl\Data\Entity\Info\MilitaryAffiliation();
	}
}