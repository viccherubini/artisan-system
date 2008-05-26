<?php

class Artisan_Config_Array extends Artisan_Config {

	private $_array = NULL;
	
	public function __construct($array) {
		$this->_array = $array;
	}
	
	/**
	 * Sets the current configuration file if not set in the constructor,
	 * or if it needs to be reset.
	 */
	public function set($cfg) {
		$this->_array = $cfg;
	}
	
	/**
	 * Loads the specified XML configuration file. Loads in the XML
	 * library at runtime if necessary.
	 */
	public function load() {
		parent::internalize($this->_array, $this);
	}
}

?>