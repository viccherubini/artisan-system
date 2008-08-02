<?php

Artisan_Library::load('Config/Monitor');
Artisan_Library::load('Config/Exception');

abstract class Artisan_Config {
	/**
	 * Load the configuration.
	 */
	abstract protected function _load($source);

	/**
	 * Take an array and turn it into into an object like:
	 * $arr = array(
	 *     'a' => 'value',
	 *     'b' => 'value2',
	 *     'c' => array( 'd' => 'value3' )
	 * );
	 * To:
	 * $object->a has the value 'value', and
	 * $object->c->d has the value 'value3' in it.
	 * This way, one can easily internalize a config
	 * array to access each element easily.
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
}

?>