<?php

/**
 * AbstractEntity
 * Abstract class for all entities
 *
 * @package     ScholarshipOwl\Data\Entity
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	07. October 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Data\Entity;


abstract class AbstractEntity {

    protected $_rawData = array();

    /**
     * @deprecated SHOUL NOT BE USED!!!
     * @param array|null $row
     */
    public function __construct(array $row = null)
    {
        $this->loadFromRow($row);
    }

    /**
     * @param $row
     * @return mixed
     */
	abstract public function populate($row);

    /**
     * @return array
     */
	abstract public function toArray();

    public function loadFromRow(array $row = null)
    {
        if ($row) {
            $this->_rawData = $row;
            $this->populate($row);
        }

        return $this;
    }

    /**
     * @param null $key
     * @return mixed
     */
    public function getRawData($key = null)
    {
        $result = $this->_rawData;

        if ($key !== null) {
            $result = array_key_exists($key, $this->_rawData) ? $this->_rawData[$key] : null;
        }

        return $result;
    }
}
