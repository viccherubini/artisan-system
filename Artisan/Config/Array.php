<?php

class Artisan_Config_Array extends Artisan_Config {
	/**
	 * Default constructor, sets the internal configuration array.
	 */
	public function __construct($array) {
		if ( true === is_array($array) ) {
			$this->_load($array);
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
	protected function _load($source) {
		if ( true === is_array($source) ) {
			$this->_init($source);
		}
	}
}

?>
