<?php

/**
 * AbstractModel
 *
 * @package     ScholarshipOwl\Http
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	12. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Http;


abstract class AbstractModel implements \ArrayAccess, \Iterator {
	private $data;
	
	abstract public function send();
	
	
	public function __construct() {
		$this->data = array();
	}
	
	public function getData() {
		return $this->data;
	}
	
	public function setData($data) {
		$this->data = $data;
        return $this;
	}
	
	public function __get($key) {
		if(array_key_exists($key, $this->data)) {
			return $this->data[$key];
		}
		
		return null;
	}
	
	public function __set($key, $value) {
		$this->data[$key] = $value;
	}
	
	public function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}
	
	public function offsetGet($offset) {
		return isset($this->data[$offset]) ? $this->data[$offset] : null;
	}
	
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}
	
	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}
	
	public function rewind() {
		reset($this->data);
	}
	
	public function current() {
		return current($this->data);
	}
	
	public function key() {
		return key($this->data);
	}
	
	public function next() {
		return next($this->data);
	}
	
	public function valid() {
		return key($this->data) !== null;
	}
}
