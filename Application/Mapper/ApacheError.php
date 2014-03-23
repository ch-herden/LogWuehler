<?php

namespace Application\Mapper;

use Application\Helper\Language;

/**
 * Apache error log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class ApacheError extends AbstractLogFile {

	/**
	 * Ini keyword
	 */
	const KEYWORD = 'apache.error';
	
	/**
	 * Instance
	 * @var \Application\Mapper\ApacheError 
	 */
	private static $_instance;

	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	protected function _getKeyword() {
		return self::KEYWORD;
	}

	/**
	 * Get log properties
	 * @return array
	 */
	public function getProperties() {
		return array(
			Language::translate('cn.log.show.table.head.apache.error.time'),
			Language::translate('cn.log.show.table.head.apache.error.level'),
			Language::translate('cn.log.show.table.head.apache.error.message')
		);
	}

	/**
	 * Singleton
	 * @return \Application\Mapper\ApacheError 
	 */
	public function getInstance() {
		if (!isset(self::$_instance)) {
			self::$_instance = new ApacheError();
		}
		return self::$_instance;
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

		$time = strtotime(substr($line, 5, 20));
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

}
