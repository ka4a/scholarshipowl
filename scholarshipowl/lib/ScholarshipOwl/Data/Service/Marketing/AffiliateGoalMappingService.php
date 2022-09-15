<?php
/**
 * Author: Ivan Krkotic (ivan@siriomedia.com)
 * Date: 30/9/2015
 */

namespace ScholarshipOwl\Data\Service\Marketing;


use ScholarshipOwl\Data\Entity\Marketing\AffiliateGoalMapping;
use ScholarshipOwl\Data\Service\AbstractService;

class AffiliateGoalMappingService extends AbstractService implements IAffiliateGoalMappingService {
	public function getAffiliateGoalMapping($affiliateGoalMappingId){
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE affiliate_goal_mapping_id = ?", self::TABLE_AFFILIATE_GOAL_MAPPING);
		$resultSet = $this->query($sql, array($affiliateGoalMappingId));

		foreach ($resultSet as $row) {
			$result = new AffiliateGoalMapping();
			$result->populate($row);
		}

		return $result;
	}

	public function getAffiliateGoalMappings(){
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_AFFILIATE_GOAL_MAPPING);
		$resultSet = $this->query($sql);

		foreach ($resultSet as $row) {
			$entity = new AffiliateGoalMapping();
			$entity->populate($row);
			$result[] = $entity;
		}

		return $result;
	}

	public function addAffiliateGoalMapping(AffiliateGoalMapping $affiliateGoalMapping){
		return $this->saveAffiliateGoalMapping($affiliateGoalMapping);
	}
	public function updateAffiliateGoalMapping(AffiliateGoalMapping $affiliateGoalMapping){
		return $this->saveAffiliateGoalMapping($affiliateGoalMapping, false);
	}
	public function saveAffiliateGoalMapping(AffiliateGoalMapping $affiliateGoalMapping, $insert = true){
		$result = 0;

		$affiliateGoalMappingId = $affiliateGoalMapping->getAffiliateGoalMappingId();
		$data = $affiliateGoalMapping->toArray();

		unset($data["affiliate_goal_mapping_id"]);

		if($insert == true) {
			$this->insert(self::TABLE_AFFILIATE_GOAL_MAPPING, $data);
			$affiliateGoalMappingId = $this->getLastInsertId();

			$result = $affiliateGoalMappingId;
		}
		else {
			unset($data["affiliate_goal_mapping_id"]);
			$result = $this->update(self::TABLE_AFFILIATE_GOAL_MAPPING, $data, array("affiliate_goal_mapping_id" => $affiliateGoalMappingId));
		}

		return $result;
	}

	public function deleteAffiliateGoalMapping($affiliateGoalMappingId){
		return $this->execute(sprintf("DELETE FROM %s WHERE affiliate_goal_mapping_id = ?", self::TABLE_AFFILIATE_GOAL_MAPPING), array($affiliateGoalMappingId));
	}

	public function getAffiliateGoalMappingByParam($urlParameter) {
		$result = "";

		$sql = sprintf("SELECT * FROM %s WHERE url_parameter = ?", self::TABLE_AFFILIATE_GOAL_MAPPING);
		$resultSet = $this->query($sql, array($urlParameter));

		foreach ($resultSet as $row) {
			$entity = new AffiliateGoalMapping();
			$entity->populate($row);

			$result = $entity;
		}

		return $result;
	}
}