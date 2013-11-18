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

}