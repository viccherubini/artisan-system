<?php

class Artisan_Config_Xml extends Artisan_Config {
	/**
	 * Default constructor, sets the internal configuration XML.
	 */
	public function __construct($source) {
		$this->_load($source);
	}
	
	/**
	 * Default destructor, unsets the internal configuration XML.
	 */
	public function __destruct() { }
	
	/**
	 * Loads the specified XML configuration file. Loads in the XML
	 * library at runtime if necessary.
	 */
	protected function _load($source) {
		if ( false === Artisan_Library::exists('Xml') ) {
			Artisan_Library::load('Xml');
		}
		
		Artisan_Xml::load($source);
		$xml = Artisan_Xml::toArray();
		
		$this->_init($xml);
	}
}

?>