<?php

/**
 * Singleton plugin class 
 */
class Artisan_Controller_Plugin {
	private static $INST = NULL;
	
	private function __construct() { }
	
	public function __destruct() { }
	
	private function __clone() { }
	
	public function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		
		return self::$INST;
	}
	
	public function register(&$plugin, $name) {
		if ( true === is_object($plugin) ) {
			if ( false === isset($this->{$name}) ) {
				$this->{$name} = &$plugin;
			}
		}
		
		return true;
	}
}

?>
