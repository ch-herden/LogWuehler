<?php

namespace Application\Controller;

use Application\Mapper;

/**
 * Log controller
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class LogController {

	/**
	 * Apache error mapper
	 * @var \Application\Mapper\ApacheError
	 */
	protected $_apacheErrorMapper;

	/**
	 * Apache access mapper
	 * @var \Application\Mapper\ApacheAccess
	 */
	protected $_apacheAccessMapper;

	/**
	 * Nginx error mapper
	 * @var \Application\Mapper\NginxError
	 */
	protected $_nginxErrorMapper;
	
	/**
	 * Nginx access mapper
	 * @var \Application\Mapper\NginxAccess
	 */
	protected $_nginxAccessMapper;


	/**
	 * Init mappers
	 */
	public function __construct() {
		$this->_apacheErrorMapper = new Mapper\ApacheError();
		$this->_apacheAccessMapper = new Mapper\ApacheAccess();
		$this->_nginxErrorMapper = new Mapper\NginxError();
		$this->_nginxAccessMapper = new Mapper\NginxAccess();
	}

	/**
	 * Index Action
	 * @return array
	 */
	public function indexAction() {
		return array(
			'apacheErrorLogFiles' => $this->_apacheErrorMapper->getFileList(),
			'apacheAccessLogFiles' => $this->_apacheAccessMapper->getFileList(),
			'nginxErrorLogFiles' => $this->_nginxErrorMapper->getFileList(),
			'nginxAccessLogFiles' => $this->_nginxAccessMapper->getFileList()
		);
	}

	/**
	 * Action for show log
	 * @return array
	 */
	public function showAction() {
		return array(
			'startTime' => date("d.m.Y H:i", time() - 600),
			'endTime' => date("d.m.Y H:i"),
			'fileType' => filter_input(INPUT_GET, 'file'),
			'file' => filter_input(INPUT_GET, 'dir')
		);
	}

	/**
	 * Action for get log data
	 * @return array
	 */
	public function dataAction() {
		switch (filter_input(INPUT_GET, 'filetype')) {
			case 'apache.error':
				$mapper = $this->_apacheErrorMapper;
				break;
			case 'apache.access':
				$mapper = $this->_apacheAccessMapper;
				break;
		}

		$file = base64_decode(filter_input(INPUT_POST, 'file'));
		$startTime = filter_input(INPUT_POST, 'time_start');
		$endTime = filter_input(INPUT_POST, 'time_end');
		$term = filter_input(INPUT_POST, 'term');
		
		echo json_encode(array(
			'header' => $mapper->getProperties(),
			'content' => $mapper->getLogEntries($file, $startTime, $endTime, $term)
		));

		return array(
			'layout' => false,
			'view' => false
		);
	}

}
