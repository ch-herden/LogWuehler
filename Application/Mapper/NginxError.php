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
		$time = strtotime(substr($line, 0, 19));
		if (true !== $this->_validateTime($time, $timeStart, $timeEnd)) {
			return false;
		}

		$line = substr($line, 20);
		$errorStr = explode(': ', strstr($line, ', client:', true), 2);
		$message = $this->_validateMessage($errorStr[1], $term);
		if ($message === false) {
			return false;
		}
		
		$matches = array();
		preg_match("|\[([a-z]+)\] (\d+)#(\d+)|", $errorStr[0], $matches);

		return array(
			date("d.m.Y H:i:s", $time),
			$matches[1],
			$message
		);
	}

}
