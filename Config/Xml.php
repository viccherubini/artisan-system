<?php

/**
 * Loads in configuration information through an XML file.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Config_Xml extends Artisan_Config {
	/**
	 * Default constructor, sets the internal configuration XML.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Config_Xml Object
	 */
	public function __construct($source) {
		$this->_load($source);
	}
	
	/**
	 * Default destructor, unsets the internal configuration XML.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() { }
	
	/**
	 * Loads the specified XML configuration file. Loads in the XML
	 * library at runtime if necessary.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
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
