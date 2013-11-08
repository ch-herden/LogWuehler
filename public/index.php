<?php

/**
 * Index class
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 */
class Index {

	/**
	 * Perform
	 */
	public function __construct() {
		$this->_checkPhpVersion();
		$this->_registerNamespaces();
	}

	/**
	 * Check PHP version
	 * 
	 * @throws Exception
	 */
	protected function _checkPhpVersion() {
		if (version_compare(phpversion(), "5.3.0", "<")) {
			throw new Exception('PHP version 5.3 or above is required to run this code. Please upgrade to continue.');
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
	
}

$index = new Index();