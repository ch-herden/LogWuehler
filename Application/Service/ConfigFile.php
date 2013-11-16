<?php

namespace Application\Service;

/**
 * Create configfile
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class ConfigFile {

	/**
	 * POST params
	 * @var array
	 */
	protected $_params;

	/**
	 * Error array
	 * @var array
	 */
	protected $_error;

	/**
	 * File Error
	 * @var array
	 */
	protected $_file;

	/**
	 * init
	 * @param array $params
	 */
	public function __construct($params) {
		$this->_params = $this->_filterParams($params);
		$this->_error = array(
			'file' => false,
			'apacheError' => false,
			'apacheAccess' => false
		);
		$this->_file = array();
	}

	/**
	 * Perform service
	 * @return boolean | array
	 */
	public function perform() {
		if (strlen($this->_params['apacheErrorPath']) > 0) {
			$apacheErrorLogPath = realpath($this->_params['apacheErrorPath']);
			if (!$this->_checkDirectoryPermissions($apacheErrorLogPath)) {
				$this->_error['apacheError'] = true;
				return $this->_error;
			}
			$this->_buildConfigString('apache.error', $apacheErrorLogPath, $this->_params['apacheErrorKeyword']);
		}

		if (strlen($this->_params['apacheAccessPath']) > 0) {
			$apacheAccessLogPath = realpath($this->_params['apacheAccessPath']);
			if (!$this->_checkDirectoryPermissions($apacheAccessLogPath)) {
				$this->_error['apacheAccess'] = true;
				return $this->_error;
			}
			$this->_buildConfigString('apache.access', $apacheAccessLogPath, $this->_params['apacheAccessKeyword']);
		}

		$this->_write();
		return true;
	}

	/**
	 * Filter params
	 * @param array $params
	 * @return array
	 */
	protected function _filterParams($params) {
		foreach ($params as $key => $param) {
			$param[$key] = strip_tags(trim($param));
		}

		return $params;
	}

	/**
	 * Check execute permission of directory
	 * @param String $dir
	 */
	protected function _checkDirectoryPermissions($dir) {
		$content = @scandir($dir);
		if (!is_array($content) || !is_readable($dir)) {
			return false;
		}

		return true;
	}

	/**
	 * Build string for config file
	 * @param String $cat
	 * @param String $path
	 * @param String $keyword
	 */
	protected function _buildConfigString($cat, $path, $keyword) {
		$this->_file[] = '[' . $cat . ']';
		$this->_file[] = 'path="' . $path . '"';
		$this->_file[] = 'keyword="' . $keyword . '"';
	}

	/**
	 * Write config file
	 */
	protected function _write() {
		$handle = fopen(APPLICATION_PATH . '/Application/config/app.ini', "w");
		foreach ($this->_file as $line) {
			fwrite($handle, $line . "\n");
		}
		fclose($handle);
	}

}
