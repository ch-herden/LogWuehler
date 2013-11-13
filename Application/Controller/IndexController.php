<?php

namespace Application\Controller;

use Application\Mapper;

/**
 * Index controller
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class IndexController {
	
	/**
	 * Index Action
	 * @return array
	 */
	public function indexAction() {
		$fileMapper = new Mapper\File();
		return array(
			'apacheErrorLogFiles' => $fileMapper->getApacheErrorLogFiles()
		);
	}
	
	/**
	 * Action for show log
	 * @return array
	 */
	public function showAction() {
		return array();
	}
	
	/**
	 * Action for get log data
	 * @return array
	 */
	public function dataAction() {
		
		echo json_encode(array(
			array(
				'level' => 'warn',
				'time' => '05.11.2013 12:49:23',
				'msg' => "mod_fcgid: stderr: #0 /srv/www/xlocator.de/v2/live/webclient/library/Zend/Translate/Adapter.php(646): Zend_Translate_Adapter_Gettext->_loadTranslationData('/srv/www/xlocat...', 'de_DE', Array), referer: http://www2.xlocator.de/"
			)
		));
		
		return array(
			'layout' => false,
			'view' => false
		);
	}
	
}