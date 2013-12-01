<?php

namespace Application\Mapper;

/**
 * Nginx access log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class NginxAccess extends LogFile {
	
	/**
	 * Get log properties
	 * @return array
	 */
	public function getProperties() {
		
	}
	
	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	protected function _getKeyword() {
		
	}
	
	/**
	 * Get an entry of a log file
	 * @param String $line
	 * @param String $timeStart
	 * @param String $timeEnd
	 * @param String $term
	 * @return boolean | array
	 */
	protected function _getEntry($line, $timeStart, $timeEnd, $term) {
		
	}
	
}