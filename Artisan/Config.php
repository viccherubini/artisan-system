<?php

Artisan_Library::load('Config/Monitor');
Artisan_Library::load('Config/Exception');

abstract class Artisan_Config {

	/**
	 * Set the configuration.
	 */
	abstract public function set($cfg);
	
	/**
	 * Load the configuration.
	 */
	abstract public function load();

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
	public static function internalize($root, &$t) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$t->$k = $t;
				self::internalize($v, $t);
			} else {
				$t->$k = $v;
			}
		}
	}
}

?>
