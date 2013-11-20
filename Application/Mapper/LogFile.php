<?php

namespace Application\Mapper;

/**
 * Abstract log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
abstract class LogFile {

	/**
	 * Ini keyword
	 * @var String
	 */
	protected $_iniKey;

	/**
	 * Construct
	 */
	public function __construct() {
		$this->_iniKey = $this->_getKeyword();
	}

	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	abstract protected function _getKeyword();

	/**
	 * Get log properties
	 * @return array
	 */
	abstract public function getProperties();

	/**
	 * Get entries of a log file in an array
	 * @param String $file
	 * @param String $timeStart
	 * @param String $timeEnd
	 * @param String $term
	 * @return array
	 */
	abstract public function getLogEntries($file, $timeStart, $timeEnd, $term);

	/**
	 * Get list of log files
	 * @return array
	 */
	public function getFileList() {
		$config = parse_ini_file(APPLICATION_PATH . '/Application/config/app.ini', true);
		$path = $config[$this->_iniKey]['path'];
		$keyword = $config[$this->_iniKey]['keyword'];

		return $this->_getFilesFromDirectory($path, $keyword);
	}

	/**
	 * Get files from directory and filter by keyword
	 * @param String $path
	 * @param String $keyword
	 * @return array
	 */
	protected function _getFilesFromDirectory($path, $keyword) {
		$files = array();

		$data = scandir($path);
		if (!is_array($data)) {
			return $files;
		}

		unset($data[0], $data[1]);
		foreach ($data as $value) {
			if (is_dir($path . '/' . $value)) {
				$dirData = scandir(realpath($path . '/' . $value));
				unset($dirData[0], $dirData[1]);
				foreach ($dirData as $dirValue) {
					if (strpos($value, $keyword) !== false || $keyword == '') {
						$files[base64_encode($path . '/' . $value . '/' . $dirValue)] = $value . '/' . $dirValue;
					}
				}
			} else {
				if (strpos($value, $keyword) !== false || $keyword == '') {
					$files[base64_encode($path . '/' . $value)] = $value;
				}
			}
		}

		return $files;
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
