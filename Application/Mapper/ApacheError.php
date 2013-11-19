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
	 * @param String $file
	 * @param String $timeStart
	 * @param String $timeEnd
	 * @param String $term
	 * @return array
	 */
	public function getLogEntries($file, $timeStart, $timeEnd, $term) {
		$entries = array();

		$fileArr = explode(".", $file);
		if ($fileArr[count($fileArr) - 1] === 'gz') {
			$handle = gzopen($file, "r");
			while (!gzeof($handle)) {
				$line = gzgets($handle, 8192);
			}

			$result = $this->_getEntry($line, $timeStart, $timeEnd, $term);
			if (is_array($result)) {
				$entries[] = $result;
			}

			gzclose($handle);
		} else {
			$handle = fopen($file, "r");
			while (!feof($handle)) {
				$line = fgets($handle);

				$result = $this->_getEntry($line, $timeStart, $timeEnd, $term);
				if (is_array($result)) {
					$entries[] = $result;
				}
			}

			fclose($handle);
		}

		return $entries;
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
		$timeArr = null;
		$result = array();

		preg_match('~^\[(.*?)\]~', $line, $timeArr);
		if (empty($timeArr[1])) {
			return false;
		}
		$time = strtotime(substr($timeArr[1], 4));
		if (true !== $this->_validateTime($time, $timeStart, $timeEnd)) {
			return false;
		}

		if (1 === preg_match('/^\[(.*)]\ \[(.*)]\ \[(.*)]\ (.*)$/', $line, $result)) {
			$level = $result[2];
			$message = $result[4];
		} else if (1 === preg_match('/^\[(.*)]\ \[(.*)]\ (.*)$/', $line, $result)) {
			$level = $result[2];
			$message = $result[3];
		} else {
			return false;
		}

		$message = $this->_validateMessage($message, $term);
		if ($message === false) {
			return false;
		}

		return array(
			date("d.m.Y H:i:s", $time),
			$level,
			$message
		);
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
