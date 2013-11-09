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
	 * View params
	 * @var array
	 */
	protected $_view;

	/**
	 * Perform
	 */
	public function __construct() {
		define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../'));
		
		$this->_checkPhpVersion();
		$this->_registerNamespaces();
		$this->_setRoute();
		$this->_checkConfigFile();
		$this->_initRoute();
		$this->_renderLayout();
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
		require_once APPLICATION_PATH . '/vendor/SplClassLoader/SplClassLoader.php';
		$loader = new SplClassLoader('Application', APPLICATION_PATH);
		$loader->register();
	}

	/**
	 * Set controller and action name from url
	 */
	protected function _setRoute() {
		$url = filter_input(INPUT_SERVER, 'REQUEST_URI');

		$position = strpos($url, '?');
		if ($position !== false) {
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
		if (array_key_exists(0, $url)) {
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
		if (array_key_exists(1, $url)) {
			$this->_action = $url[1];
		} else {
			$this->_action = 'index';
		}
	}

	/**
	 * Check config file
	 */
	protected function _checkConfigFile() {
		if (!file_exists(APPLICATION_PATH . '/Application/config/app.ini') && $this->_controller != 'install') {
			$this->_controller = 'install';
			$this->_action = 'index';
		}
	}

	protected function _initRoute() {
		$conNamespace = '\Application\Controller\\' . ucfirst($this->_controller) . 'Controller';
		$action = $this->_action . 'Action';

		if (!class_exists($conNamespace)) {
			$this->_sendNotFound();
		}

		$controller = new $conNamespace();

		if (!method_exists($controller, $action)) {
			$this->_sendNotFound();
		}

		$this->_view = $controller->{$action}();
		if (!is_array($this->_view)) {
			$this->_view = array();
		}
	}

	/**
	 * Render layout
	 * @throws Exception
	 */
	protected function _renderLayout() {
		if (array_key_exists('layout', $this->_view) && $this->_view['layout'] == false) {
			$this->_renderView();
			return;
		}

		if (!file_exists(APPLICATION_PATH . '/Application/View/Layout/layout.phtml')) {
			throw new Exception('layout.phtml not found');
		}

		require_once APPLICATION_PATH . '/Application/View/Layout/layout.phtml';
	}

	/**
	 * Render view
	 */
	protected function _renderView() {
		if (array_key_exists('view', $this->_view) && $this->_view['view'] == false) {
			return;
		}

		$file = APPLICATION_PATH . '/Application/View/Application/';
		$file .= $this->_controller . '/' . $this->_action . '.phtml';

		if (!file_exists($file)) {
			$this->_sendNotFound();
		}

		require_once $file;
	}

	/**
	 * Send 404 header
	 */
	protected function _sendNotFound() {
		header('HTTP/1.0 404 Not Found');
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		exit();
	}

}

$index = new Index();
