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
	protected $_entries;

	/**
	 * Get an entry by key
	 * @param String $key
	 * @return null | String
	 */
	public static function translate($key) {
		$this->_checkEntries();
		
		if(!array_key_exists($key, $this->_entries)) {
			return null;
		}
		
		return $this->_entries[$key];
	}
	
	/**
	 * Check if load entries
	 */
	protected function _checkEntries() {
		if(empty($this->_entries)) {
			$this->_entries = require_once APPLICATION_PATH . '/Application/languages/de_DE.php';
		}
	}
	
}