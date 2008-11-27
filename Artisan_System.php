<?php

@set_magic_quotes_runtime(0);

class Artisan_System {
	private static $_instance = NULL;

	private function __construct() {
	
	}
	
	private function __clone() {
	
	}
	
	public static function &getInstance() {
		if ( true === is_null(self::$_instance) || false === self::$_instance instanceof self ) {
				self::$_instance = new self;
		}
		
		return self::$_instance;
	}
	
	public function build($object_name) {
		// See if there are other parameters
		$argc = func_num_args();

		$argv = array();
		if ( $argc > 1 ) {
			// The rest of the values are arguments to pass to the constructor.
			$argv = func_get_args();
			$argv = array_slice($argv, 1);
		}
		
		// Time to reflect on ourselves for a bit.
		//if ( true === file_exists($object_name) ) {
		//
		//}
		
		// Load in the file based on the class name.
		
	}
	
	
	private function _makeName($object_name) {
		
	}
}