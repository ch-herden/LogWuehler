<?php

namespace Application\Helper;

/**
 * Language helper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class Language {
	
	/**
	 * Language entries
	 * @var array
	 */
	protected static $_entries;

	/**
	 * Get an entry by key
	 * @param String $key
	 * @return null | String
	 */
	public static function translate($key) {
		self::_checkEntries();
		
		if(!array_key_exists($key, self::$_entries)) {
			return $key;
		}
		
		return self::$_entries[$key];
	}
	
	/**
	 * Check if load entries
	 */
	protected static function _checkEntries() {
		if(empty(self::$_entries)) {
			self::$_entries = require_once APPLICATION_PATH . '/Application/languages/de_DE.php';
		}
	}
	
}