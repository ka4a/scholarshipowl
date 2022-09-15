<?php

/**
 * Storage
 *
 * @package     ScholarshipOwl\Util
 * @version     1.0
 * @author      Marko Prelic <markomys@gmail.com>
 *
 * @created    	06. April 2015.
 * @copyright  	Sirio Media
 */

namespace ScholarshipOwl\Util;


class Storage {
	const DS = DIRECTORY_SEPARATOR;
	
	const ROOT = "files";
	const MAILBOX = "mailbox";
	const AFFILIATE = "system/affiliate";
	
	
	public static function getPath($accountId, $folder) {
		return self::buildPath(array(
			self::getRootPath(),
			implode(self::DS, str_split($accountId)),
			$folder
		));
	}
	
	public static function getRootPath() {
		return self::buildPath(array(base_path(), self::ROOT));	
	}
	
	public static function getMailboxPath($accountId) {
		return self::getPath($accountId, self::MAILBOX);
	}
	
	public static function getAffiliatePath() {
		self::createPath(self::AFFILIATE);
		return self::AFFILIATE;
	}
	
	public static function saveFile($accountId, $folder, $name, $content) {
		$path = self::getPath($accountId, $folder);
		self::createPath($path);
		
		$extension = pathinfo($name, PATHINFO_EXTENSION);
		$file = $accountId . "_" . time() . "_" . md5($name) . "." . $extension;
		$fullPath = $path . self::DS . $file;
		
		if (!file_put_contents($fullPath, $content)) {
			throw new \RuntimeException("Storage file {$fullPath} can not be saved");
		}
		
		return $file;
	}
	
	public static function createPath($path) {
		if (!file_exists($path)) {
			if (!mkdir($path, 0777, true)) {
				throw new \RuntimeException("Storage path {$path} can not be created");
			}
		}
	}
	
	public static function whichFile($file) {
		$array = explode("_", $file);
		
		if (!empty($array)) {
			return $array[0];
		}
	}
	
	public static function cleanPath($path) {
		array_map("unlink", glob("{$path}/*"));
	}
	
	public static function buildPath($dirs) {
		return implode(self::DS, $dirs);
	}
}
