<?php

namespace Application\Controller;

/**
 * Index controller
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class IndexController {
	
	/**
	 * Index action
	 */
	public function indexAction() {
		header("Location: /log");
		exit();
	}
	
}