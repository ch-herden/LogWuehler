<?php

namespace Application\Mapper;

use Application\Mapper;

/**
 * Apache error log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class ApacheError extends Mapper\LogFile {

	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	protected function _getKeyword() {
		return 'apache.error';
	}

	/**
	 * Get log properties
	 * @return array
	 */
	public function getProperties() {
		return array(
			'Zeitpunkt',
			'Level',
			'Nachricht'
		);
	}

	/**
	 * Get entries of a log file in an array
	 * @return array
	 */
	public function getLogEntries($file, $timeStart, $timeEnd, $term) {
		$entries = array();
		
		$date = null;
		
		$handle = fopen($file, "r");
		
		while (!feof($handle)) {
			$line = fgets($handle);
			preg_match('~^\[(.*?)\]~', $line, $date);
			if (empty($date[1])) {
				continue;
			}
			
			$time = strtotime(substr($date[1], 4));
			if(true !== $this->_validateTime($time, $timeStart, $timeEnd)) {
				continue;
			}
					
			$entries[] = array(
				date("d.m.Y H:i:s", strtotime(substr($date[1], 4))),
				'',
				''
			);
		}

		fclose($handle);

		return $entries;
	}

	protected function _validateTime($time, $startTime, $endTime) {
		$startTime = strtotime($startTime);
		$endTime = strtotime($endTime);
		
		if($time >= $startTime && $time <= $endTime) {
			return true;
		}
		
		return false;
	}
	
}
