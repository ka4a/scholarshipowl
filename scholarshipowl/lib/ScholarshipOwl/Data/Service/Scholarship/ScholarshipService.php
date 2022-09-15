<?php

/**
 * ScholarshipService
 *
 * @package     ScholarshipOwl\Data\Service\Scholarship
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	29. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service\Scholarship;

use ScholarshipOwl\Data\Entity\Scholarship\Eligibility;
use ScholarshipOwl\Data\Entity\Scholarship\Form;
use ScholarshipOwl\Data\Entity\Scholarship\Scholarship;
use ScholarshipOwl\Data\Service\AbstractService;


class ScholarshipService extends AbstractService implements IScholarshipService {
	const CACHE_KEY_SCHOLARSHIP = "SCHOLARSHIP";
	const CACHE_KEY_SCHOLARSHIP_EXPIRE = "SCHOLARSHIP.EXPIRE";


	/**
	 * Gets All Scholarships
	 *
	 * @access public
	 * @return array
	 *
	 * @author Ivan Krkotic <ivan.krkotic@gmail.com>
	 */
	public function getScholarships() {
		$result = array();

		$sql = sprintf("SELECT * FROM %s;", self::TABLE_SCHOLARSHIP);

		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Scholarship();
			$entity->populate($row);

			$result[$entity->getScholarshipId()] = $entity;
		}

		return $result;
	}

	/**
	 * Gets Scholarship By Id
	 *
	 * @param $scholarshipId int
	 * @param $essays boolean
	 * @param $fields boolean
	 * @param $eligibilities boolean
	 *
	 * @access public
	 * @return \ScholarshipOwl\Data\Entity\Scholarship\Scholarship
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getScholarship($scholarshipId) {
		$data = $this->getByColumn(self::TABLE_SCHOLARSHIP, "scholarship_id", $scholarshipId);
		if (!$data) {
		    return null;
		}

		$result = new Scholarship();
		$result->populate($data);

		return $result;
	}

	/**
	 * Gets Active Automatic scholarship By Id
	 *
	 *
	 * @access public
	 * @return \ScholarshipOwl\Data\Entity\Scholarship\Scholarship
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getActiveAutomaticScholarship() {
		$result = new Scholarship();

		$sql = sprintf("
				SELECT *
				FROM %s
				WHERE is_active = ?
				AND is_automatic = ?
				AND DATE(expiration_date) >= DATE(NOW());
			", self::TABLE_SCHOLARSHIP);

		$resultSet = $this->query($sql, array(1, 1));
		foreach($resultSet as $row) {
			$row = (array) $row;

			$result->populate($row);
		}

		return $result;
	}

	/**
	 * Gets Scholarship Eligibilities
	 *
	 * @param $scholarshipId int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getScholarshipEligibilities($scholarshipId) {
		$result = array();

		$sql = sprintf("SELECT eligibility_id, scholarship_id, field_id, type, value, is_optional FROM %s WHERE scholarship_id = ?", self::TABLE_ELIGIBILITY);
		$resultSet = $this->query($sql, array($scholarshipId));
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Eligibility();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}


    /**
     * @param array $scholarshipIds
     *
     * @return array
     */
	public function getScholarshipsInfo(array $scholarshipIds)
    {
		$result = [];
		$sql = sprintf("
			SELECT
				scholarship_id, title, url, expiration_date,
				amount, awards, application_type, is_active, is_free
			FROM %s
			WHERE scholarship_id IN(" . implode(array_fill(0, count($scholarshipIds), "?"), ",") . ")
			", self::TABLE_SCHOLARSHIP
		);

        if (!empty($scholarshipIds)) {
            $resultSet = $this->query($sql, $scholarshipIds);
            foreach ($resultSet as $row) {
                $row = (array)$row;

                $entity = new Scholarship();
                $entity->populate($row);

                $result[$entity->getScholarshipId()] = $entity;
            }
        }

		return $result;
	}


	/**
	 * Gets Scholarships Data (CACHED)
	 *
	 * @param $scholarshipIds array
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getScholarshipsData($scholarshipIds, $onlyActive = true, $expiredFromDate = null) {
		$result = array();
		$notFoundInCache = array();

		if (!is_array($scholarshipIds)) {
			$scholarshipIds = array($scholarshipIds);
		}


		// Search Cache
		foreach ($scholarshipIds as $scholarshipId) {
			$cacheKey = sprintf("%s_%d", self::CACHE_KEY_SCHOLARSHIP, $scholarshipId);
			$data = $this->getFromCache($cacheKey);

			if (empty($data)) {
				$notFoundInCache[] = $scholarshipId;
			}
			else {
				$result[$scholarshipId] = $data;
			}
		}


		// Search DB For Not Found In Cache
		if (!empty($notFoundInCache)) {
			$table = self::TABLE_SCHOLARSHIP;
			$columns = "scholarship_id, title, url, application_type, expiration_date, created_date, amount, description, terms_of_service_url, is_free, files_alowed, is_recurrent";
			$where = "scholarship_id IN(" . implode(array_fill(0, count($notFoundInCache), "?"), ",") . ")";

			if ($onlyActive == true) {
				 $where .= " AND is_active = 1 ";
			}

			if (isset($expiredFromDate)) {
				$where .= " AND DATE(expiration_date) > '$expiredFromDate' ";
			}
			else {
				$where .= " AND DATE(expiration_date) >= DATE(NOW()) ";
			}

			$sql = sprintf("SELECT %s FROM %s WHERE %s", $columns, $table, $where);
			$resultSet = $this->query($sql, $notFoundInCache);
			foreach ($resultSet as $row) {
				$row = (array) $row;
				$row["essays"] = array();

				$result[$row["scholarship_id"]] = $row;
			}


			// Filtered If Some Are Not Active When $onlyActive == true
			$filteredIds = array_keys($result);

			if (!empty($filteredIds)) {
				$table = self::TABLE_ESSAY;
				$columns = "essay_id, scholarship_id, title, description, min_words, max_words, min_characters, max_characters";
				$where = "scholarship_id IN(" . implode(array_fill(0, count($filteredIds), "?"), ",") . ")";

				$sql = sprintf("SELECT %s FROM %s WHERE %s", $columns, $table, $where);
				$resultSet = $this->query($sql, $filteredIds);
				foreach ($resultSet as $row) {
					$row = (array) $row;

					$scholarshipId = $row["scholarship_id"];
					$essayId = $row["essay_id"];

					$result[$scholarshipId]["essays"][$essayId] = $row;
				}

				// Save To Cache
				foreach ($filteredIds as $scholarshipId) {
					$cacheKey = sprintf("%s_%d", self::CACHE_KEY_SCHOLARSHIP, $scholarshipId);
					$this->setToCache($cacheKey, $result[$scholarshipId], 60);
				}
			}
		}

		return $result;
	}


	/**
	 * Saves Scholarship Information Data
	 *
	 * @param $scholarship ScholarshipOwl\Data\Entity\Scholarship\Scholarship
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveScholarshipInformation(Scholarship $scholarship) {
		$result = 0;

		$scholarshipId = $scholarship->getScholarshipId();
		$data = array(
			"title" => $scholarship->getTitle(),
			"url" => $scholarship->getUrl(),
			"expiration_date" => $scholarship->getExpirationDate(),
			"amount" => $scholarship->getAmount(),
			"up_to" => $scholarship->getUpTo(),
			"awards" => $scholarship->getAwards(),
			"description" => $scholarship->getDescription(),
			"terms_of_service_url" => $scholarship->getTermsOfServiceUrl(),
			"privacy_policy_url" => $scholarship->getPrivacyPolicyUrl(),
            "status" => $scholarship->getStatus(),
			"is_free" => $scholarship->isFree(),
			"is_automatic" => $scholarship->isAutomatic()
		);

		if (empty($scholarshipId)) {
			$data["created_date"] = date("Y-m-d H:i:s");
			$data["last_updated_date"] = "0000-00-00 00:00:00";

			$this->insert(self::TABLE_SCHOLARSHIP, $data);
			$scholarshipId = $this->getLastInsertId();

			$result = $scholarshipId;
		}
		else {
			$data["last_updated_date"] = date("Y-m-d H:i:s");
			$result = $this->update(self::TABLE_SCHOLARSHIP, $data, array("scholarship_id" => $scholarshipId));
		}

		return $result;
	}


	/**
	 * Saves Scholarship Email Application Data
	 *
	 * @param $scholarship ScholarshipOwl\Data\Entity\Scholarship\Scholarship
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveScholarshipEmailApplication(Scholarship $scholarship) {
		$result = 0;

		$scholarshipId = $scholarship->getScholarshipId();
		$data = array(
			"application_type" => Scholarship::APPLICATION_TYPE_EMAIL,
			"apply_url" => $scholarship->getApplyUrl(),
			"email" => $scholarship->getEmail(),
			"email_subject" => $scholarship->getEmailSubject(),
			"email_message" => $scholarship->getEmailMessage(),
			"last_updated_date" => date("Y-m-d H:i:s"),
			'files_alowed' => $scholarship->getFilesAlowed(),
            'send_to_private' => $scholarship->getSendToPrivate() ? 1 : 0,
		);

		$result = $this->update(self::TABLE_SCHOLARSHIP, $data, array("scholarship_id" => $scholarshipId));
		return $result;
	}


	/**
	 * Saves Scholarship None Application Data
	 *
	 * @param $scholarship ScholarshipOwl\Data\Entity\Scholarship\Scholarship
	 * @access public
	 * @return int
	 *
	 * @author Faist Ilya <markomys@gmail.com>
	 */
	public function saveScholarshipNoneApplication(Scholarship $scholarship) {
		$result = 0;

		$scholarshipId = $scholarship->getScholarshipId();
		$data = array(
			"application_type" => Scholarship::APPLICATION_TYPE_NONE,
			"apply_url" => NULL,
			"email" => NULL,
			"email_subject" => NULL,
			"email_message" => NULL,
			"form_method" =>  NULL,
			"form_action" =>  NULL,
			"last_updated_date" => date("Y-m-d H:i:s"),
			'files_alowed' => $scholarship->getFilesAlowed()
		);

		$result = $this->update(self::TABLE_SCHOLARSHIP, $data, array("scholarship_id" => $scholarshipId));
		return $result;
	}


	/**
	 * Saves Scholarship Online Application Data
	 *
	 * @param $scholarship ScholarshipOwl\Data\Entity\Scholarship\Scholarship
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveScholarshipOnlineApplication(Scholarship $scholarship) {
		$result = 0;

		try {
			$this->beginTransaction();

			$scholarshipId = $scholarship->getScholarshipId();
			$data = array(
				"application_type" => Scholarship::APPLICATION_TYPE_ONLINE,
				"apply_url" => $scholarship->getApplyUrl(),
				"form_method" => $scholarship->getFormMethod(),
				"form_action" => $scholarship->getFormAction(),
				"last_updated_date" => date("Y-m-d H:i:s"),
				'files_alowed' => $scholarship->getFilesAlowed()
			);

			$result = $this->update(self::TABLE_SCHOLARSHIP, $data, array("scholarship_id" => $scholarshipId));
			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}


	/**
	 * Saves Scholarship Essays
	 *
	 * @param $scholarshipId int
	 * @param $essays array
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveScholarshipEssays($scholarshipId, $essays) {
		$result = 0;

		try {
			$this->beginTransaction();

			$oldEssays = array();
			$newEssays = array();

			foreach ($essays as $essay) {
				$essayId = $essay->getEssayId();

				if (!empty($essayId)) {
					$oldEssays[$essayId] = $essay;
				}
				else {
					$newEssays[] = $essay;
				}
			}

			// Delete Old Essays Except Those Sent
			$sql = sprintf("DELETE FROM %s WHERE scholarship_id = ?", self::TABLE_ESSAY);
			$bind = array($scholarshipId);

			if (!empty($oldEssays)) {
				$oldEssayIds = array_keys($oldEssays);
				$marks = implode(array_fill(0, count($oldEssayIds), "?"), ",");

				$sql .= sprintf(" AND essay_id NOT IN(%s)", $marks);
				foreach ($oldEssayIds as $oldEssayId) {
					$bind[] = $oldEssayId;
				}
			}

			$result += $this->execute($sql, $bind);


			// Update Old Essays
			foreach ($oldEssays as $essay) {
				$data = $essay->toArray();
				$data["scholarship_id"] = $scholarshipId;

				$essayId = $data["essay_id"];
				unset($data["essay_id"]);

				$result += $this->update(self::TABLE_ESSAY, $data, array("essay_id" => $essayId));
			}


			// Insert New Essays
			foreach ($newEssays as $essay) {
				$data = $essay->toArray();
				$data["scholarship_id"] = $scholarshipId;

				unset($data["essay_id"]);
				$this->insert(self::TABLE_ESSAY, $data);

				$result++;
			}


			// Update Scholarship Last Updated Date
			$result += $this->update(self::TABLE_SCHOLARSHIP, array("last_updated_date" => date("Y-m-d H:i:s")), array("scholarship_id" => $scholarshipId));

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}


	/**
	 * Saves Scholarship Eligibilities
	 *
	 * @param $scholarshipId int
	 * @param $eligibilities array
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function saveScholarshipEligibilities($scholarshipId, $eligibilities) {
		$result = 0;

		try {
			$this->beginTransaction();


			// Delete All Eligibility
			$sql = sprintf("DELETE FROM %s WHERE scholarship_id = ?", self::TABLE_ELIGIBILITY);
			$result += $this->execute($sql, array($scholarshipId));


			// Insert New Eligibilities
			foreach($eligibilities as $eligibility) {
				$data = $eligibility->toArray();
				unset($data["eligibility_id"]);

				$data["scholarship_id"] = $scholarshipId;
                $data['value'] = json_encode($data['value']);
				$this->insert(self::TABLE_ELIGIBILITY, $data);
				$result++;
			}


			// Update Scholarship Last Updated Date
			$result += $this->update(self::TABLE_SCHOLARSHIP, array("last_updated_date" => date("Y-m-d H:i:s")), array("scholarship_id" => $scholarshipId));

			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}


	/**
	 * Disables Scholarships
	 *
	 * @param $scholarshipIds array
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function disableScholarships($scholarshipIds) {
		$result = 0;

		try {
			$this->beginTransaction();

			$marks = implode(array_fill(0, count($scholarshipIds), "?"), ",");
			$sql = sprintf("UPDATE %s SET is_active = 0 WHERE scholarship_id IN(%s)", self::TABLE_SCHOLARSHIP, $marks);
			$result = $this->execute($sql, $scholarshipIds);

			$this->commit();
		}
		catch(\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}


	/**
	 * Gets Scholarship Form Fields
	 *
	 * @param $scholarshipId int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getForm($scholarshipId) {
		$result = array();

		$sql = sprintf("SELECT * FROM %s WHERE scholarship_id = ?", self::TABLE_FORM);
		$resultSet = $this->query($sql, array($scholarshipId));

		foreach ($resultSet as $row) {
			$row = (array) $row;

			$entity = new Form();
			$entity->populate($row);

			$result[] = $entity;
		}

		return $result;
	}


	/**
	 * Sets Scholarship Form Field
	 *
	 * @param $form ScholarshipOwl\Data\Entity\Scholarship\Form
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function setFormField(Form $form) {
		$result = 0;

		try {
			$this->beginTransaction();

			$sql = sprintf("DELETE FROM %s WHERE scholarship_id = ? AND form_field = ?", self::TABLE_FORM);
			$this->execute($sql, array($form->getScholarshipId(), $form->getFormField()));

			$data = $form->toArray();
			unset($data["form_id"]);
			$this->insert(self::TABLE_FORM, $data);

			$result = $this->getLastInsertId();
			$this->commit();
		}
		catch (\Exception $exc) {
			$this->rollback();
			throw $exc;
		}

		return $result;
	}


	/**
	 * Gets Scholarship Form Field
	 *
	 * @param $form ScholarshipOwl\Data\Entity\Scholarship\Form
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getFormField($scholarshipId, $formField) {
		$result = array();

		$sql = sprintf("SELECT * FROM %s WHERE scholarship_id = ? AND form_field = ?", self::TABLE_FORM);
		$resultSet = $this->query($sql, array($scholarshipId, $formField));

		foreach ($resultSet as $row) {
			$row = (array) $row;
			$result = $row;
		}

		return $result;
	}


	/**
	 * Deletes Scholarship Form Field
	 *
	 * @param $scholarshipId int
	 * @param $formField string
	 * @access public
	 * @return int
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function deleteFormField($scholarshipId, $formField) {
		$sql = sprintf("DELETE FROM %s WHERE scholarship_id = ? AND form_field = ?", self::TABLE_FORM);
		$result = $this->execute($sql, array($scholarshipId, $formField));

		return $result;
	}


	/**
	 * Gets Free Scholarships
	 *
	 * @param $accountId int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getFreeScholarships($accountId = null) {
		return $this->getScholarshipsByPricing(1, $accountId);
	}


	/**
	 * Gets Paid Scholarships
	 *
	 * @param $accountId int
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getPaidScholarships($accountId = null) {
		return $this->getScholarshipsByPricing(0, $accountId);
	}


	/**
	 * Gets Expired Scholarships
	 *
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function getExpiredScholarships() {
		$result = array();

		$sql = sprintf("
			SELECT scholarship_id, title, url, amount, expiration_date
			FROM %s
			WHERE DATE(expiration_date) < DATE(NOW())
			AND is_active = 1
		", self::TABLE_SCHOLARSHIP);

		$resultSet = $this->query($sql);
		foreach($resultSet as $row) {
			$row = (array) $row;

			$entity = new Scholarship();
			$entity->populate($row);

			$result[$entity->getScholarshipId()] = $entity;
		}

		return $result;
	}


	/**
	 * Checks If Scholarships Expire In X Days
	 *
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function checkScholarshipsExpiration($scholarshipIds, $daysInterval) {
		$result = array();

		$sql = sprintf("
			SELECT scholarship_id
			FROM %s
			WHERE scholarship_id IN(" . implode(array_fill(0, count($scholarshipIds), "?"), ",") . ")
			AND DATE(expiration_date) < DATE_ADD(NOW(), INTERVAL %d DAY)
			AND is_active = 1
		", self::TABLE_SCHOLARSHIP, $daysInterval);

		$resultSet = $this->query($sql, $scholarshipIds);
		foreach ($resultSet as $row) {
			$result[] = $row->scholarship_id;
		}

		return $result;
	}


	/**
	 * Checks If All Scholarships Expire In X Days
	 *
	 * @access public
	 * @return array
	 *
	 * @author Marko Prelic <markomys@gmail.com>
	 */
	public function checkAllScholarshipsExpiration($daysInterval) {
		$result = array();

		$cacheKey = sprintf("%s_%d", self::CACHE_KEY_SCHOLARSHIP_EXPIRE, $daysInterval);
		$result = $this->getFromCache($cacheKey);

		if (empty($result)) {
			$result = array();

			$sql = sprintf("
				SELECT scholarship_id
				FROM %s
				WHERE DATE(expiration_date) < DATE_ADD(NOW(), INTERVAL %d DAY)
				AND is_active = 1
			", self::TABLE_SCHOLARSHIP, $daysInterval);

			$resultSet = $this->query($sql);
			foreach ($resultSet as $row) {
				$result[] = $row->scholarship_id;
			}

			$this->setToCache($cacheKey, $result, 120);
		}

		return $result;
	}


	private function getScholarshipsByPricing($isFree = 0, $accountId = null) {
		$result = array();

		$conditions = "";
		$bind = array();

		if(!empty($accountId)) {
			$conditions = sprintf("
				AND scholarship_id NOT IN (SELECT scholarship_id FROM %s WHERE account_id = ?)
				", self::TABLE_APPLICATION
			);
			$bind[] = $accountId;
		}

		$sql = sprintf("
			SELECT scholarship_id, title, amount, url, terms_of_service_url, expiration_date
			FROM %s
			WHERE DATE(expiration_date) >= DATE(NOW())
			AND is_active = 1
			AND is_free = %d
			%s
		", self::TABLE_SCHOLARSHIP, $isFree, $conditions);

		$resultSet = $this->query($sql, $bind);
		foreach($resultSet as $row) {
			$entity = new Scholarship();
			$entity->populate((array) $row);

			$result[$entity->getScholarshipId()] = $entity;
		}

		return $result;
	}

	public function getScholarshipSummaryPrice($scholarshipIds){
		$total = 0;
		if(!empty($scholarshipIds) && is_array($scholarshipIds)){
			$ids = implode(',',$scholarshipIds);
			$sql = sprintf("
			SELECT amount
			FROM %s
			WHERE scholarship_id in ({$ids})", self::TABLE_SCHOLARSHIP);

			$resultSet = $this->query($sql);
			foreach($resultSet as $row) {
				$total += $row->amount;
			}
		}
		return $total;
	}
}
