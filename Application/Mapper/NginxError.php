<?php

namespace Application\Mapper;

use Application\Helper\Language;

/**
 * Nginx error log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class NginxError extends LogFile {
	
	/**
	 * Get log properties
	 * @return array
	 */
	public function getProperties() {
		return array(
			Language::translate('cn.log.show.table.head.nginx.error.time'),
			Language::translate('cn.log.show.table.head.nginx.error.level'),
			Language::translate('cn.log.show.table.head.nginx.error.message')
		);
	}
	
	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	protected function _getKeyword() {
		return 'nginx.error';
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
		$result = array();

		$time = strtotime(substr($line, 0, 19));
		if (true !== $this->_validateTime($time, $timeStart, $timeEnd)) {
			return false;
		}
		
//		die(var_dump($time, substr($line, 0, 19), $line));
		
		return $result;
	}

}