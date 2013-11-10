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
	 */
	public function indexAction() {
		$fileMapper = new Mapper\File();
		return array(
			'apacheErrorLogFiles' => $fileMapper->getApacheErrorLogFiles()
		);
	}
	
}