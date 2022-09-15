<?php

/**
 * AbstractService
 *
 * @package     ScholarshipOwl\Data\Service
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Service;


use ScholarshipOwl\Data\Entity\AbstractEntity;

abstract class AbstractService implements IDDL {

    /**
     * @return \Illuminate\Database\Connection
     */
    public function connection()
    {
        return \DB::connection();
    }

    protected function beginTransaction() {
		return $this->connection()->beginTransaction();
	}

	protected function commit() {
		return $this->connection()->commit();
	}

	protected function rollback() {
		return $this->connection()->rollback();
	}

	protected function execute($sql, $params = array()) {
		return $this->connection()->statement($sql, $params);
	}

	protected function query($sql, $params = array()) {
		return $this->connection()->select($this->connection()->raw($sql), $params);
	}

	protected function insert($table, $data, $where = array()) {
		$sql = "INSERT INTO %s(%s) VALUES (%s)";
		$columns = array();
		$values = array();
		$params = array();

		foreach($data as $key => $value) {
			$values[] = "?";
			$params[] = $value;
		}

		$columns = implode(",", array_keys($data));
		$values = implode(",", $values);

		$query = sprintf($sql, $table, $columns, $values);
		return $this->connection()->insert($query, $params);
	}

	protected function insertBulk($table, $columns, $data, $where = array()) {
       	$sql = "INSERT INTO %s(%s) VALUES %s";

		$values = $params = $par = array();

		foreach($data as $k) {
			$values = array();

            foreach ($k as $value) {
              	$values[] = "?";
				$params[] = $value;
            }

            $par[] = '(' . implode(',', $values) . ')';
		}

        $columns = implode(",", $columns);
		$values = implode(",", $par);

		$query = sprintf($sql, $table, $columns, $values);
		return $this->connection()->insert($query, $params);
    }

	protected function update($table, $data, $where = array()) {
		$sql = "UPDATE %s SET %s %s";
		$columns = array();
		$params = array();
		$conditions = array();

		foreach($data as $key => $value) {
			$columns[] = sprintf("%s = ?", $key);
			$params[] = $value;
		}

		foreach($where as $key => $value) {
			$conditions[] = sprintf("%s = ?", $key);
			$params[] = $value;
		}

		$columns = implode(",", $columns);
		$conditions = implode(" AND ", $conditions);

		if(!empty($conditions)) {
			$conditions = sprintf("WHERE %s", $conditions);
		}
		
		$query = sprintf($sql, $table, $columns, $conditions);
		return $this->connection()->update($query, $params);
	}

	protected function delete($table, $where = array()) {
		$sql = "DELETE FROM %s %s";
		$conditions = array();
		$params = array();

		foreach($where as $key => $value) {
			$conditions[] = sprintf("%s = ?", $key);
			$params[] = $value;
		}

		$conditions = implode(" AND ", $conditions);
		if(!empty($conditions)) {
			$conditions = sprintf("WHERE %s", $conditions);
		}

		$query = sprintf($sql, $table, $conditions);
		return $this->connection()->delete($query, $params);
	}

	protected function getByColumn($table, $column, $value, $columns = array("*")) {
		$result = null;

		$sql = sprintf("SELECT %s FROM %s WHERE %s = ?", implode(",", $columns), $table, $column, $value);
		$resultSet = $this->query($sql, array($value));
		foreach($resultSet as $row) {
			$result = $row;
		}

		return $result;
	}

	protected function getEntityByColumn($entity, $table, $column, $value, $columns = array("*")) {
		$result = null;

		$data = $this->getByColumn($table, $column, $value, $columns);
		if(!empty($data)) {
            /** @var AbstractEntity $result */
            $result = new $entity();
            $result->populate($data);
		}

		return $result;
	}

	protected function getLastInsertId() {
		return $this->connection()->getPdo()->lastInsertId();
	}

	protected function getFromCache($key) {
		return \Cache::get($key);
	}

	protected function setToCache($key, $value, $timeInMinutes = null) {
		return \Cache::put($key, $value, $timeInMinutes * 60);
	}

	protected function removeFromCache($key) {
		return \Cache::forget($key);
	}

	protected function logInfo($data) {
		return \Log::info($data);
	}

	protected function logWarning($data) {
		return \Log::warning($data);
	}

	protected function logError($data) {
		return \Log::error($data);
	}
}
