<?php
/**
 * DaneMediaService
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created    	29. March 2016.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use Illuminate\Database\QueryException;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Marketing\DaneMediaCampaign;
use ScholarshipOwl\Data\Entity\Marketing\DaneMediaCampus;
use ScholarshipOwl\Data\Entity\Marketing\DaneMediaProgram;
use ScholarshipOwl\Data\Service\AbstractService;

class DaneMediaService extends AbstractService implements IDaneMediaService {
	public function getDaneMediaCampaign($daneMediaCampaignId){
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE dane_media_campaign_id = ?", self::TABLE_DANE_MEDIA_CAMPAIGN);
		$resultSet = $this->query($sql, array($daneMediaCampaignId));

		foreach ($resultSet as $row) {
			$result = new DaneMediaCampaign();
			$result->populate($row);
		}

		return $result;
	}

	public function getDaneMediaCampaigns(){
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_DANE_MEDIA_CAMPAIGN);
		$resultSet = $this->query($sql);

		foreach ($resultSet as $row) {
			$entity = new DaneMediaCampaign();
			$entity->populate($row);
			$result[] = $entity;
		}

		return $result;
	}

	public function getActiveDaneMediaCampaigns(){
		$result = array();
		try {
			$sql = sprintf("SELECT * FROM %s WHERE active = 1;", self::TABLE_DANE_MEDIA_CAMPAIGN);
			$resultSet = $this->query($sql);

			foreach ($resultSet as $row) {
				$entity = new DaneMediaCampaign();
				$entity->populate($row);
				$result[] = $entity;
			}
		}catch(QueryException $e){
			\Log::error($e->getMessage());
		}

		return $result;
	}

	public function getDaneMediaCampus($daneMediaCampusId){
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE submission_value = ?", self::TABLE_DANE_MEDIA_CAMPUS);
		$resultSet = $this->query($sql, array($daneMediaCampusId));

		foreach ($resultSet as $row) {
			$result = new DaneMediaCampus();
			$result->populate($row);
		}

		return $result;
	}

	public function getDaneMediaCampuses(){
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_DANE_MEDIA_CAMPUS);
		$resultSet = $this->query($sql);

		foreach ($resultSet as $row) {
			$entity = new DaneMediaCampus();
			$entity->populate($row);
			$result[] = $entity;
		}

		return $result;
	}

	public function getDaneMediaProgram($daneMediaProgramId){
		$result = null;

		$sql = sprintf("SELECT * FROM %s WHERE dane_media_campaign_id = ?", self::TABLE_DANE_MEDIA_PROGRAM);
		$resultSet = $this->query($sql, array($daneMediaProgramId));

		foreach ($resultSet as $row) {
			$result = new DaneMediaProgram();
			$result->populate($row);
		}

		return $result;
	}
	public function getDaneMediaPrograms(){
		$result = array();

		$sql = sprintf("SELECT * FROM %s", self::TABLE_DANE_MEDIA_PROGRAM);
		$resultSet = $this->query($sql);

		foreach ($resultSet as $row) {
			$entity = new DaneMediaProgram();
			$entity->populate($row);
			$result[] = $entity;
		}

		return $result;
	}

	public function getAvailableCampaignsForAccount(Account $account, $withCampuses = true){
		$result = array();

		try{
			$sql = "SELECT 
					    dmc.*, dmcam.dane_media_campus_id, dmcam.submission_value as dmcam_submission_value, dmcam.display_value, 
					    (SELECT 
					            COALESCE(SUM(count), 0)
					        FROM
					            dane_media_campaign_allocation dmca
					        WHERE
					            type = 'month'
					                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
					                AND dmca.dane_media_campaign_id = dmc.dane_media_campaign_id) AS monthly_allocation,
					    (SELECT 
					            COALESCE(SUM(count), 0)
					        FROM
					            dane_media_campaign_allocation dmca
					        WHERE
					            type = 'day'
					                AND DATE(date) = DATE(CURRENT_DATE())
					                AND dmca.dane_media_campaign_id = dmc.dane_media_campaign_id) AS daily_allocation
					FROM
					    dane_media_campaign dmc
					        LEFT JOIN
					    `dane_media_campaign_allocation` dmca ON dmc.dane_media_campaign_id = dmca.dane_media_campaign_id
					        LEFT JOIN
					    `dane_media_campus` dmcam ON dmc.dane_media_campaign_id = dmcam.dane_media_campaign_id
					        LEFT JOIN
					    `dane_media_program` dmp ON dmc.dane_media_campaign_id = dmp.dane_media_campaign_id
					WHERE
					    dmc.active = 1
					        AND (dmc.daily_cap IS NULL
					        OR ((SELECT 
					            COALESCE(SUM(count), 0)
					        FROM
					            dane_media_campaign_allocation dmca
					        WHERE
					            type = 'day'
					                AND DATE(date) = DATE(CURRENT_DATE())
					                AND dmca.dane_media_campaign_id = dmc.dane_media_campaign_id) = 0)
					        OR ((SELECT 
					            COALESCE(SUM(count), 0)
					        FROM
					            dane_media_campaign_allocation dmca
					        WHERE
					            type = 'day'
					                AND DATE(date) = DATE(CURRENT_DATE())
					                AND dmca.dane_media_campaign_id = dmc.dane_media_campaign_id) < dmc.daily_cap))
					        AND (dmc.monthly_cap IS NULL
					        OR ((SELECT 
					            COALESCE(SUM(count), 0)
					        FROM
					            dane_media_campaign_allocation dmca
					        WHERE
					            type = 'month'
					                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
					                AND dmca.dane_media_campaign_id = dmc.dane_media_campaign_id) = 0)
					        OR ((SELECT 
					            COALESCE(SUM(count), 0)
					        FROM
					            dane_media_campaign_allocation dmca
					        WHERE
					            type = 'month'
					                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
					                AND dmca.dane_media_campaign_id = dmc.dane_media_campaign_id) < dmc.monthly_cap))
					        AND 
					        	(dmcam.zip LIKE CONCAT ('%', ?, '%') OR dmcam.zip IS NULL)
					        AND 
					        	(dmp.zip LIKE CONCAT ('%', ?, '%') OR dmp.zip IS NULL)
					ORDER BY daily_allocation ASC, monthly_allocation ASC";

			$resultSet = \DB::select($sql, array($account->getProfile()->getZip(), $account->getProfile()->getZip()));

			$lastCampaignId = 0;
			$entity = new DaneMediaCampaign();
			foreach ($resultSet as $row) {
				if($lastCampaignId != $row->dane_media_campaign_id){
					$entity = new DaneMediaCampaign();
					$lastCampaignId = $row->dane_media_campaign_id;
				}

				$entity->populate($row);

				if ($withCampuses) {
					$campus = new DaneMediaCampus();
					$campus->populate($row);
                    $campus->setSubmissionValue($row->dmcam_submission_value);
					$entity->campuses[$campus->getDaneMediaCampusId()] = $campus;
				}
				$result[$entity->getDaneMediaCampaignId()] = $entity;
			}
		}catch(\Exception $e){
			\Log::error($e->getMessage());
		}

		return $result;
	}

	public function getAvailablePrograms(Account $account, $data){
		$result = array();
		$where = "";

		foreach ($data as $column => $value){
			if($column != "dane_media_campaign_id" && $column != "/dane-media-programs") {
				if (!empty($value)) {
                    $column = addslashes($column);
					$value = str_replace("%", "", $value);
					$where .= "AND ($column LIKE '%%$value%%' OR $column IS NULL) ";
				} else {
                    $column = addslashes($column);
					$where .= "AND $column IS NULL ";
				}
			}
		}
		$where .= "AND (state LIKE '%%".$account->getProfile()->getState()->getAbbreviation()."%%' OR state IS NULL) ";
		$where .= "AND (zip LIKE '%%".$account->getProfile()->getZip()."%%' OR zip IS NULL) ";

		$sql = sprintf("SELECT * FROM %s WHERE dane_media_campaign_id = ? ".$where, self::TABLE_DANE_MEDIA_PROGRAM);

		$resultSet = $this->query($sql, array($data["dane_media_campaign_id"]));


		foreach ($resultSet as $row){
			$entity = new DaneMediaProgram();
			$entity->populate($row);

			$result[] = $row;
		}

		return $result;
	}

	public function updateCampaignCapping($campaignId, $type = "month"){
		$sql = sprintf("INSERT INTO %s 
            (`dane_media_campaign_id`,
                `type`,
                `date`,
                `count`)
            VALUES (
                ?,
                ?,
                ".($type == "month"?"ADDDATE(LAST_DAY(SUBDATE(NOW(), INTERVAL 1 MONTH)),1)":"DATE(NOW())").",
                1
            )
            ON DUPLICATE KEY UPDATE `count` = `count` +1;", self::TABLE_DANE_MEDIA_CAMPAIGN_ALLOCATION);

		$this->query($sql, array($campaignId, $type));
	}
}