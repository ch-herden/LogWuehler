<?php

namespace Application\Controller;

/**
 * Install controller
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class InstallController {
	
	public function indexAction() {
		$this->_checkConfigFile();
		$view = array(
			'permission' => $this->_checkRights(),
			'error' => false,
			'success' => false
		);
		$view['permission'] = $this->_checkRights();
		
		$postParams = filter_input_array(INPUT_POST);
		if(!is_null($postParams)) {
			$path = realpath($postParams['apacheErrorLog']);
			$view['error'] = is_readable($path);
			if($view['error'] === false) {
				$handle = fopen(APPLICATION_PATH . '/Application/config/app.ini', "w");
				fwrite($handle, '[paths]' . "\n");
				fwrite($handle, 'apache.error="' . $path . '"' . "\n");
				fclose($handle);
				
				$view['success'] = true;
			}
		}
		
		return $view;
	}
	
	protected function _checkConfigFile() {
		if (file_exists(APPLICATION_PATH . '/Application/config/app.ini')) {
			header("Location: /");
			exit();
		}
	}
	
	protected function _checkRights() {
		return is_writable(APPLICATION_PATH . '/Application/config');
	}
	
}