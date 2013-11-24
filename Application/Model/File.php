<?php

namespace Application\Model;

/**
 * File
 * 
 * @author Chris Herden <contact@chris-herden.de>
 * @copyright (c) 2013, Chris Herden
 * @license http://opensource.org/licenses/MIT
 */
class File {
	
	/**
	 * Filename
	 * @var String
	 */
	protected $_name;
	
	/**
	 * Path
	 * @var String
	 */
	protected $_path;
	
	/**
	 * Filesize
	 * @var int 
	 */
	protected $_size;
	
	/**
	 * Get filename
	 * @return String
	 */
	public function getName() {
		return $this->_name;
	}

	/**
	 * Get path
	 * @return String
	 */
	public function getPath() {
		return $this->_path;
	}

	/**
	 * Get filesize
	 * @return int
	 */
	public function getSize() {
		return $this->_size;
	}

	/**
	 * Set filename
	 * @param String $name
	 * @return \Application\Model\File
	 */
	public function setName($name) {
		$this->_name = $name;
		return $this;
	}

	/**
	 * Set path
	 * @param String $path
	 * @return \Application\Model\File
	 */
	public function setPath($path) {
		$this->_path = $path;
		return $this;
	}

	/**
	 * Set filesize
	 * @param int $size
	 * @return \Application\Model\File
	 */
	public function setSize($size) {
		$this->_size = $size;
		return $this;
	}
	
}