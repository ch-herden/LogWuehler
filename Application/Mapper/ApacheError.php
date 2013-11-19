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
		$time = null;
		$result = array();
		$entries = array();

		$handle = fopen($file, "r");

		while (!feof($handle)) {
			$line = fgets($handle);

			preg_match('~^\[(.*?)\]~', $line, $time);
			if (empty($time[1])) {
				continue;
			}
			$time = strtotime(substr($time[1], 4));
			if (true !== $this->_validateTime($time, $timeStart, $timeEnd)) {
				continue;
			}

			if (1 === preg_match('/^\[(.*)]\ \[(.*)]\ \[(.*)]\ (.*)$/', $line, $result)) {
				$level = $result[2];
				$message = $result[4];
			} else if (1 === preg_match('/^\[(.*)]\ \[(.*)]\ (.*)$/', $line, $result)) {
				$level = $result[2];
				$message = $result[3];
			} else {
				continue;
			}

			$message = $this->_validateMessage($message, $term);
			if ($message === false) {
				continue;
			}

			$entries[] = array(
				date("d.m.Y H:i:s", $time),
				$level,
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

		if ($time >= $startTime && $time <= $endTime) {
			return true;
		}

		return false;
	}

	/**
	 * Validate message by term
	 * @param String $message
	 * @param String $term
	 * @return boolean
	 */
	protected function _validateMessage($message, $term) {
		if (strlen($term) < 1) {
			return $message;
		}

		if (strpos($message, $term) !== false) {
			return $message;
		}

		return false;
	}

}
