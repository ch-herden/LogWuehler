<?php

namespace Application\Mapper;

/**
 * FileMapper
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class File {
	
	/**
	 *
	 * @var array
	 */
	protected $_paths;
	
	/**
	 * Read ini file
	 */
	public function __construct() {
		$config = parse_ini_file(APPLICATION_PATH . '/Application/config/app.ini', true);
		$this->_paths = $config['paths'];
	}
	
	/**
	 * Get files in apache error log path
	 * @return array
	 */
	public function getApacheErrorLogFiles(){
		$path = $this->_paths['apache.error'];
		return $this->getFilesFromDirectory($path);
	}
	
	/**
	 * Get files in a path
	 * @param String $path
	 * @return array
	 */
	public function getFilesFromDirectory($path) {
		$files = array();
		
		$data = scandir($path);
		unset($data[0], $data[1]);
		foreach($data as $value) {
			if(is_dir($path . '/' . $value)) {
				$dirData = scandir(realpath($path . '/' . $value));
				unset($dirData[0], $dirData[1]);
				foreach($dirData as $dirValue) {
					$files[base64_encode($path . '/' . $value . '/' . $dirValue)] = $value . '/' . $dirValue;
				}
			} else {
				$files[base64_encode($path . '/' . $value)] = $value;
			}
		}
		
		return $files;
	}
	
}