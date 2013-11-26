<?php

namespace Application\Mapper;

/**
 * Apache access log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class ApacheAccess extends LogFile {

	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	protected function _getKeyword() {
		return 'apache.access';
	}

	/**
	 * Get log properties
	 * @return array
	 */
	public function getProperties() {
		return array(
			'IP',
			'Zeitpunkt',
			'Request',
			'HTTP Code',
			'Referer',
			'Useragent'
		);
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
		$data = str_getcsv($line, " ", '"', '\\');
		if (!is_array($data)) {
			return false;
		}

		$time = strtotime(ltrim(rtrim($data[3] . ' ' . $data[4], ']'), '['));
		if (true !== $this->_validateTime($time, $timeStart, $timeEnd)) {
			return false;
		}

		if ($this->_validateMessage($data['9'], $term) === false && $this->_validateMessage($data[0], $term) === false) {
			return false;
		}

		return array(
			$data[0],
			date("d.m.Y H:i:s", $time),
			$data[5],
			$data[6],
			$data[8],
			$data[9]
		);
	}

}
