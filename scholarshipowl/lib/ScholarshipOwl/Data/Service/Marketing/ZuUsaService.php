<?php
/**
 * ZuUsaService Class
 *
 * @package     ScholarshipOwl\Data\Service\Marketing
 * @version     1.0
 * @author      Ivan Krkotic <ivan@siriomedia.com>
 *
 * @created        18. July 2016.
 * @copyright    ScholarshipOwl
 */

namespace ScholarshipOwl\Data\Service\Marketing;

use Illuminate\Database\QueryException;
use ScholarshipOwl\Data\Entity\Account\Account;
use ScholarshipOwl\Data\Entity\Marketing\ZuUsaCampaign;
use ScholarshipOwl\Data\Entity\Marketing\ZuUsaCampus;
use ScholarshipOwl\Data\Entity\Marketing\ZuUsaProgram;
use ScholarshipOwl\Data\Service\AbstractService;

class ZuUsaService extends AbstractService implements IZuUsaService
{
    public function getZuUsaCampaign($zuUsaCampaignId)
    {
        $result = null;

        $sql = sprintf("SELECT * FROM %s WHERE zu_usa_campaign_id = ?", self::TABLE_ZU_USA_CAMPAIGN);
        $resultSet = $this->query($sql, array($zuUsaCampaignId));

        foreach ($resultSet as $row) {
            $result = new ZuUsaCampaign();
            $result->populate($row);
        }

        return $result;
    }

    public function getZuUsaCampaigns()
    {
        $result = array();

        $sql = sprintf("SELECT * FROM %s", self::TABLE_ZU_USA_CAMPAIGN);
        $resultSet = $this->query($sql);

        foreach ($resultSet as $row) {
            $entity = new ZuUsaCampaign();
            $entity->populate($row);
            $result[] = $entity;
        }

        return $result;
    }

    public function getActiveZuUsaCampaigns()
    {
        $result = array();
        try {
            $sql = sprintf("SELECT * FROM %s WHERE active = 1;", self::TABLE_ZU_USA_CAMPAIGN);
            $resultSet = $this->query($sql);

            foreach ($resultSet as $row) {
                $entity = new ZuUsaCampaign();
                $entity->populate($row);
                $result[] = $entity;
            }
        } catch (QueryException $e) {
            \Log::error($e->getMessage());
        }

        return $result;
    }

    public function getZuUsaCampus($zuUsaCampusId)
    {
        $result = null;

        $sql = sprintf("SELECT * FROM %s WHERE submission_value = ?", self::TABLE_ZU_USA_CAMPUS);
        $resultSet = $this->query($sql, array($zuUsaCampusId));

        foreach ($resultSet as $row) {
            $result = new ZuUsaCampus();
            $result->populate($row);
        }

        return $result;
    }

    public function getZuUsaCampuses()
    {
        $result = array();

        $sql = sprintf("SELECT * FROM %s", self::TABLE_ZU_USA_CAMPUS);
        $resultSet = $this->query($sql);

        foreach ($resultSet as $row) {
            $entity = new ZuUsaCampus();
            $entity->populate($row);
            $result[] = $entity;
        }

        return $result;
    }

    public function getZuUsaProgram($zuUsaProgramId)
    {
        $result = null;

        $sql = sprintf("SELECT * FROM %s WHERE zu_usa_campaign_id = ?", self::TABLE_ZU_USA_PROGRAM);
        $resultSet = $this->query($sql, array($zuUsaProgramId));

        foreach ($resultSet as $row) {
            $result = new ZuUsaProgram();
            $result->populate($row);
        }

        return $result;
    }

    public function getZuUsaPrograms()
    {
        $result = array();

        $sql = sprintf("SELECT * FROM %s", self::TABLE_ZU_USA_PROGRAM);
        $resultSet = $this->query($sql);

        foreach ($resultSet as $row) {
            $entity = new ZuUsaProgram();
            $entity->populate($row);
            $result[] = $entity;
        }

        return $result;
    }

    public function getAvailableCampaignsForAccount(Account $account, $withCampuses = true)
    {
        $result = array();

        try {
            $sql = "SELECT 
    zuc.*,
    `zucam`.`zu_usa_campus_id`,
    `zucam`.`submission_value` AS zucam_submission_value,
    `zucam`.`display_value`,
    (SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campaign_allocation zuca
        WHERE
            type = 'month'
                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                AND zuca.zu_usa_campaign_id = zuc.zu_usa_campaign_id) AS monthly_allocation,
    (SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campaign_allocation zuca
        WHERE
            type = 'day'
                AND DATE(date) = DATE(CURRENT_DATE())
                AND zuca.zu_usa_campaign_id = zuc.zu_usa_campaign_id) AS daily_allocation
FROM
    zu_usa_campaign zuc
        LEFT JOIN
    `zu_usa_campaign_allocation` zuca ON zuc.zu_usa_campaign_id = zuca.zu_usa_campaign_id
        RIGHT JOIN
    `zu_usa_campus` zucam ON zuc.zu_usa_campaign_id = zucam.zu_usa_campaign_id
        RIGHT JOIN
    `zu_usa_program` zup ON CAST(`zucam`.`submission_value` AS CHAR (255)) = CAST(`zup`.`campus` AS CHAR (255))
WHERE
    zuc.active = 1 AND zucam.is_active = 1
        AND (zuc.daily_cap IS NULL
        OR zuc.daily_cap = ''
        OR ((SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campaign_allocation zuca
        WHERE
            type = 'day'
                AND DATE(date) = DATE(CURRENT_DATE())
                AND zuca.zu_usa_campaign_id = zuc.zu_usa_campaign_id) = 0)
        OR ((SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campaign_allocation zuca
        WHERE
            type = 'day'
                AND DATE(date) = DATE(CURRENT_DATE())
                AND zuca.zu_usa_campaign_id = zuc.zu_usa_campaign_id) < zuc.daily_cap))
        AND (zuc.monthly_cap IS NULL
        OR zuc.monthly_cap = ''
        OR ((SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campaign_allocation zuca
        WHERE
            type = 'month'
                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                AND zuca.zu_usa_campaign_id = zuc.zu_usa_campaign_id) = 0)
        OR ((SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campaign_allocation zuca
        WHERE
            type = 'month'
                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                AND zuca.zu_usa_campaign_id = zuc.zu_usa_campaign_id) < zuc.monthly_cap))
        AND (zucam.monthly_cap IS NULL
        OR zucam.monthly_cap = ''
        OR ((SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campus_allocation zucamp
        WHERE
            type = 'month'
                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                AND CAST(`zucamp`.`zu_usa_campus` AS CHAR (255)) = CAST(`zucam`.`submission_value` AS CHAR (255))) = 0)
        OR ((SELECT 
            COALESCE(SUM(count), 0)
        FROM
            zu_usa_campus_allocation zucamp
        WHERE
            type = 'month'
                AND DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(NOW(), '%Y-%m')
                AND CAST(`zucamp`.`zu_usa_campus` AS CHAR (255)) = CAST(`zucam`.`submission_value` AS CHAR (255))) < zucam.monthly_cap))
                AND 
					        	(zucam.zip LIKE CONCAT ('%', ?, '%') OR zucam.zip IS NULL OR zucam.zip = '')
					        AND 
					        	(zup.zip LIKE CONCAT ('%', ?, '%') OR zup.zip IS NULL OR zup.zip = '')
					        AND 
                              (zup.state LIKE CONCAT ('%', ?, '%') OR zup.state IS NULL OR zup.state = '')
ORDER BY daily_allocation ASC , monthly_allocation ASC;";

            $resultSet = \DB::select($sql, array(
                $account->getProfile()->getZip(),
                $account->getProfile()->getZip(),
                $account->getProfile()->getState()->getAbbreviation()
            ));

            $lastCampaignId = 0;
            $entity = new ZuUsaCampaign();
            foreach ($resultSet as $row) {
                if ($lastCampaignId != $row->zu_usa_campaign_id) {
                    $entity = new ZuUsaCampaign();
                    $lastCampaignId = $row->zu_usa_campaign_id;
                }

                $entity->populate($row);

                if ($withCampuses) {
                    $campus = new ZuUsaCampus();
                    $campus->populate($row);
                    $campus->setSubmissionValue($row->zucam_submission_value);
                    $entity->campuses[$campus->getZuUsaCampusId()] = $campus;
                }
                $result[$entity->getZuUsaCampaignId()] = $entity;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $result;
    }

    public function getAvailablePrograms(Account $account, $data)
    {
        $result = array();
        $where = "";

        foreach ($data as $column => $value) {
            if ($column != "zu_usa_campaign_id" && $column != "/zuusa-programs") {
                if (!empty($value)) {
                    $column = addslashes($column);
                    $value = str_replace("%", "", $value);
                    $where .= "AND ($column LIKE '%%$value%%' OR $column IS NULL OR $column = '') ";
                } else {
                    $column = addslashes($column);
                    $where .= "AND $column IS NULL ";
                }
            }
        }
        $where .= "AND (state LIKE '%%" . $account->getProfile()->getState()->getAbbreviation() . "%%' OR state IS NULL OR state = '') ";
        $where .= "AND (zip LIKE '%%" . $account->getProfile()->getZip() . "%%' OR zip IS NULL OR zip = '') ";

        $sql = sprintf("SELECT * FROM %s WHERE zu_usa_campaign_id = ? " . $where, self::TABLE_ZU_USA_PROGRAM);

        $resultSet = $this->query($sql, array($data["zu_usa_campaign_id"]));

        foreach ($resultSet as $row) {
            $entity = new ZuUsaProgram();
            $entity->populate($row);

            $result[] = $row;
        }

        return $result;
    }

    public function updateCampaignCapping($campaignId, $type = "month")
    {
        $sql = sprintf("INSERT INTO %s 
            (`zu_usa_campaign_id`,
                `type`,
                `date`,
                `count`)
            VALUES (
                ?,
                ?,
                " . ($type == "month" ? "ADDDATE(LAST_DAY(SUBDATE(NOW(), INTERVAL 1 MONTH)),1)" : "DATE(NOW())") . ",
                1
            )
            ON DUPLICATE KEY UPDATE `count` = `count` +1;", self::TABLE_ZU_USA_CAMPAIGN_ALLOCATION);

        $this->query($sql, array($campaignId, $type));
    }

    public function updateCampusCapping($campus, $type = "month")
    {
        $sql = sprintf("INSERT INTO %s 
            (`zu_usa_campus`,
                `type`,
                `date`,
                `count`)
            VALUES (
                ?,
                ?,
                " . ($type == "month" ? "ADDDATE(LAST_DAY(SUBDATE(NOW(), INTERVAL 1 MONTH)),1)" : "DATE(NOW())") . ",
                1
            )
            ON DUPLICATE KEY UPDATE `count` = `count` +1;", self::TABLE_ZU_USA_CAMPUS_ALLOCATION);

        $this->query($sql, array($campus, $type));
    }
}