<?php

//Artisan_Library::load('Config/Monitor');
Artisan_Library::load('Config/Exception');

/**
 * This class holds all configuration data that can come from different sources.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Config {
	/**
	 * Load the configuration.
	 */
	abstract protected function _load($source);

	/**
	 * Take an array and turn it into into an object like:
	 * @author vmc <vmc@leftnode.com>
	 * @code
	 * $arr = array(
	 *     'a' => 'value',
	 *     'b' => 'value2',
	 *     'c' => array( 'd' => 'value3' )
	 * );
	 * @endcode
	 * To:
	 * $object->a has the value 'value', and
	 * $object->c->d has the value 'value3' in it.
	 * This way, one can easily internalize a config
	 * array to access each element easily.
	 * @retval NULL Returns nothing.
	 */
	public function _init($root) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$this->$k = $t;
				$this->_init($v);
			} else {
				$this->$k = $v;
			}
		}
	}
	
	/**
	 * Echos out the configuration as a print_r() with a pre wrapped around it.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a string representation of $this.
	 */
	public function __toString() {
		return asfw_print_r($this, true);		
	}
}

?>
