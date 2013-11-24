<?php

namespace Application\Helper;

/**
 * File helper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class File {
	
	/**
	 * Format size
	 * @param int $size
	 * @return String
	 */
	public static function formatFilesize($size) {
		$units = array('B', 'KB', 'MB', 'GB', 'TB');
		
		for ($i = 0; $size > 1024; $i++) {
			$size /= 1024;
		}
		
		return number_format($size, 2, ',', '') . ' ' . $units[$i];
	}
	
}