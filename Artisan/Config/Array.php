<?php

class Artisan_Config_Array extends Artisan_Config {
	/**
	 * Default constructor, sets the internal configuration array.
	 */
	public function __construct($array) {
		//$this->_array = $array;
		if ( true === is_array($array) ) {
			$this->load($array);
		}
	}
	
	/**
	 * Default destructor, unsets the internal configuration array.
	 */
	public function __destruct() {
		
	}
	
	/**
	 * Loads the specified XML configuration file. Loads in the XML
	 * library at runtime if necessary.
	 */
	public function load($source) {
		if ( true === is_array($source) ) {
			$this->_init($source);
		}
	}
}

?>
