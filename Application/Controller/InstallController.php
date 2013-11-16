<?php

namespace Application\Controller;

use Application\Service;

/**
 * Install controller
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class InstallController {
	
	/**
	 * Index action
	 * @return array
	 */
	public function indexAction() {
		$this->_checkConfigFile();
		$view = array(
			'permission' => $this->_checkRights(),
			'error' => array(
				'file' => false,
				'apacheError' => false,
				'apacheAccess' => false
			),
			'success' => false
		);
		$view['permission'] = $this->_checkRights();
		
		$postParams = filter_input_array(INPUT_POST);
		if(!is_null($postParams)) {
			if(!$this->_createConfigDir()) {
				$view['error']['file'] = true;
				return $view;
			}
			
			$service = new Service\ConfigFile($postParams);
			$success = $service->perform();
			
			if($success === true) {
				$view['success'] = true;
			} else {
				$view['error'] = $success;
			}
		}
		
		return $view;
	}
	
	/**
	 * Check if config file exists
	 */
	protected function _checkConfigFile() {
		if (file_exists(APPLICATION_PATH . '/Application/config/app.ini')) {
			header("Location: /");
			exit();
		}
	}
	
	/**
	 * Check write right for config path
	 * @return boolean
	 */
	protected function _checkRights() {
		$perm = array(
			'config' => is_writable(APPLICATION_PATH . '/Application'),
			'phpversion' => true
		);
		
		if (version_compare(phpversion(), "5.3.3", "<")) {
			$perm['phpversion'] = false;
		}
		
		return $perm;
	}
	
	/**
	 * Create config directory
	 * @return boolean
	 */
	protected function _createConfigDir() {
		if(!file_exists(APPLICATION_PATH . '/Application/config')) {
			return mkdir(APPLICATION_PATH . '/Application/config');
		}
		return true;
	}
	
}