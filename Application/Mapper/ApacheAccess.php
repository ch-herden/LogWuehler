<?php

namespace Application\Mapper;

use Application\Mapper;

/**
 * Apache access log file mapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class ApacheAccess extends Mapper\LogFile {
	
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
			'Größe der Antwort',
			'Referer',
			'Useragent'
		);
	}
	
}