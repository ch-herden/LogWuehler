<?php

/**
 * Index class
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class Index {

	/**
	 * Controllername
	 * @var String
	 */
	protected $_controller;
	
	/**
	 * Actionname
	 * @var String
	 */
	protected $_action;

	/**
	 * Perform
	 */
	public function __construct() {
		$this->_checkPhpVersion();
		$this->_registerNamespaces();
		$this->_initRoute();
	}

	/**
	 * Check PHP version
	 * 
	 * @throws Exception
	 */
	protected function _checkPhpVersion() {
		if (version_compare(phpversion(), "5.3.3", "<")) {
			throw new Exception('PHP version 5.3.3 or above is required to run this code. Please upgrade to continue.');
		}
	}

	/**
	 * Register namespaces
	 */
	protected function _registerNamespaces() {
		require_once 'vendor/SplClassLoader/SplClassLoader.php';
		$loader = new SplClassLoader('Application', '../');
		$loader->register();
	}
	
	/**
	 * Set controller and action name from url
	 */
	protected function _initRoute() {
		$url = filter_input(INPUT_SERVER, 'REQUEST_URI');
		
		$position = strpos($url, '?');
		if($position !== false) {
			$url = substr($url, 0, $position);
		}
		$urlArray = explode('/', trim($url, '/'));
		
		$this->_setControllerName($urlArray);
		$this->_setActionName($urlArray);
	}
	
	/**
	 * Set controllername from url array
	 * @param array $url
	 */
	private function _setControllerName($url) {
		if(key_exists(0, $url)) {
			$this->_controller = $url[0];
		} else {
			$this->_controller = 'index';
		}
	}
	
	/**
	 * Set actionname from url array
	 * @param array $url
	 */
	private function _setActionName($url) {
		if(key_exists(1, $url)) {
			$this->_action = $url[1];
		} else {
			$this->_action = 'index';
		}
	}
	
}

$index = new Index();