<?php

/**
 * Factory
 *
 * @package     ScholarshipOwl\Vendor\SimpleHtmlDom
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	18. November 2014.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Vendor\SimpleHtmlDom;


class Factory {
	public static function get($url) {
		require_once "simple_html_dom.php";
		
		return file_get_html($url);
	}
	
	public static function getFromString($string) {
		require_once "simple_html_dom.php";
	
		return str_get_html($string);
	}
}
