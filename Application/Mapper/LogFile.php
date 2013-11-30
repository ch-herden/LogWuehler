<?php

namespace Application\Mapper;

use Application\Model;

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
	 * Get an entry of a log file
	 * @param String $line
	 * @param String $timeStart
	 * @param String $timeEnd
	 * @param String $term
	 * @return boolean | array
	 */
	abstract protected function _getEntry($line, $timeStart, $timeEnd, $term);

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
				$line = gzgets($handle, 4096);
				if ($line === false) {
					continue;
				}

				$result = $this->_getEntry($line, $timeStart, $timeEnd, $term);
				if (is_array($result)) {
					$entries[] = $result;
				}
			}

			gzclose($handle);
		} else {
			$handle = fopen($file, "r");
			while (!feof($handle)) {
				$line = fgets($handle);
				if ($line === false) {
					continue;
				}


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
	 * Get list of log files
	 * @return array | null
	 */
	public function getFileList() {
		$config = parse_ini_file(APPLICATION_PATH . '/Application/config/app.ini', true);
		if(!array_key_exists($this->_iniKey, $config)) {
			return null;
		}
		
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
						$files[] = $this->_mapFileData($path . '/' . $value, $dirValue);
					}
				}
			} else {
				if (strpos($value, $keyword) !== false || $keyword == '') {
					$files[] = $this->_mapFileData($path, $value);
				}
			}
		}

		return $files;
	}

	/**
	 * Map data to an object
	 * @param String $path
	 * @param String $file
	 * @return \Application\Model\File
	 */
	protected function _mapFileData($path, $file) {
		$obj = new Model\File();
		$obj
				->setName($file)
				->setPath($path . '/' . $file)
				->setSize(filesize($path . '/' . $file))
		;

		return $obj;
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
