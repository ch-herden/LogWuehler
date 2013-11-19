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
		$level = null;
		$message = null;
		
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
			
			preg_match('~\] \[([a-z]*?)\] \[~', $line, $level);
			preg_match('~\] (.*)$~', $line, $message);
			
			$message = $this->_validateMessage($message, $term);
			if($message === false) {
				continue;
			}
			
			$entries[] = array(
				date("d.m.Y H:i:s", strtotime(substr($date[1], 4))),
				(array_key_exists(1, $level)) ? $level[1] : '',
				$message
			);
		}

		fclose($handle);

		return $entries;
	}

	/**
	 * Validate time
	 * @param int $time
	 * @param String $startTime
	 * @param String $endTime
	 * @return boolean
	 */
	protected function _validateTime($time, $startTime, $endTime) {
		$startTime = strtotime($startTime);
		$endTime = strtotime($endTime);
		
		if($time >= $startTime && $time <= $endTime) {
			return true;
		}
		
		return false;
	}
	
	protected function _validateMessage($message, $term) {
		if(!array_key_exists(1, $message)) {
			return false;
		}
		$message = $message[1];
		
		if(strlen($term) < 1) {
			return $message;
		}
		
		if(strpos($message, $term) !== false) {
			return $message;
		}
		
		return false;
	}
	
}
