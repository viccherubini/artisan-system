<?php

class Artisan_Config_Array extends Artisan_Config {

	private $_array = NULL;
	
	/**
	 * Default constructor, sets the internal configuration array.
	 */
	public function __construct($array) {
		$this->_array = $array;
		$this->load();
	}
	
	/**
	 * Default destructor, unsets the internal configuration array.
	 */
	public function __destruct() {
		unset($this->_array);
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
		if ( true === is_array($this->_array) ) {
			parent::internalize($this->_array, $this);
		}
	
		$this->_array = NULL;
	}
}

?>
