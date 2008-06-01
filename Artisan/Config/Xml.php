<?php

class Artisan_Config_Xml extends Artisan_Config {

	private $_source = NULL;
	
	/**
	 * Default constructor, sets the internal configuration XML.
	 */
	public function __construct($source) {
		$this->_source = $source;
	}
	
	/**
	 * Default destructor, unsets the internal configuration XML.
	 */
	public function __destruct() {
		unset($this->_source);
	}
	
	/**
	 * Sets the current configuration file if not set in the constructor,
	 * or if it needs to be reset.
	 */
	public function set($cfg) {
		$this->_source = $cfg;
	}
	
	/**
	 * Loads the specified XML configuration file. Loads in the XML
	 * library at runtime if necessary.
	 */
	public function load() {
		if ( false === Artisan_Library::exists('Xml') ) {
			Artisan_Library::load('Xml');
		}
		
		Artisan_Xml::load($this->_source);
		$xml = Artisan_Xml::toArray();
		
		parent::internalize($xml, $this);
	}
}

?>