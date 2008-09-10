<?php

/**
 * Instance of the Artisan_Config class that accepts an array.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Config_Array extends Artisan_Config {
	/**
	 * Default constructor, sets the internal configuration array.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Config_Array instance.
	 */
	public function __construct($array) {
		if ( true === is_array($array) ) {
			$this->_load($array);
		}
	}
	
	/**
	 * Default destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() {
		
	}
	
	/**
	 * Loads the specified array data.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	protected function _load($source) {
		if ( true === is_array($source) ) {
			$this->_init($source);
		}
	}
}

?>
