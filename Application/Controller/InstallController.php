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
		
		$view = array();
		$view['permission'] = $this->_checkRights();
		
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