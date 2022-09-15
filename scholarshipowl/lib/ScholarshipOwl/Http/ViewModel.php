<?php

/**
 * ViewModel
 *
 * @package     ScholarshipOwl\Http
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	12. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Http;


class ViewModel extends AbstractModel {
	private $file;
	
	
	public function __construct($file = "", $data = array()) {
		parent::__construct();
		
		$this->file = $file;
		$this->setData($data);
	}

    /**
     * @return \Illuminate\Contracts\View\View
     */
	public function send() {
		return \View::make($this->getFile(), $this->getData());
	}
	
	public function setFile($file) {
		$this->file = $file;
	}
	
	public function getFile() {
		return $this->file;
	}
}
