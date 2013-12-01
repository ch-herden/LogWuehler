<?php

namespace Application\Mapper;

use Application\Helper\Language;

/**
 * Nginx access log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class NginxAccess extends LogFile {

	/**
	 * Get log properties
	 * @return array
	 */
	public function getProperties() {
		return array(
			Language::translate('cn.log.show.table.head.nginx.access.ip'),
			Language::translate('cn.log.show.table.head.nginx.access.time'),
			Language::translate('cn.log.show.table.head.nginx.access.request'),
			Language::translate('cn.log.show.table.head.nginx.access.code'),
			Language::translate('cn.log.show.table.head.nginx.access.referer'),
			Language::translate('cn.log.show.table.head.nginx.access.useragent')
		);
	}

	/**
	 * Get keyword from ini file
	 * @return String ini file key
	 */
	protected function _getKeyword() {
		return 'nginx.access';
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

		if ($this->_validateMessage($data['9'], $term) === false && $this->_validateMessage($data[8], $term) === false && $this->_validateMessage($data[5], $term) === false) {
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
