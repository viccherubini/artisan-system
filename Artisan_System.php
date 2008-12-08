<?php

@set_magic_quotes_runtime(0);

define('ARTISAN_', 'Artisan_', false);

class Artisan_System {
	private static $INST = NULL;
	
	protected static $object_list;

	private function __construct() {
	
	}
	
	private function __clone() {
	
	}
	
	/**
	 * Returns this class for usage as a singleton.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the itself.
	 */
	public static function &get() {
		if ( true === is_null(self::$INST) ) {
			self::$INST = new self;
		}
		return self::$INST;
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
	
	public function push($obj) {
		if ( false === is_object($obj) ) {
			return false;
		}
		
		if ( false === method_exists($obj, 'name') ) {
			return false;
		}
		
		$obj_name = $obj->name();
		$obj_name = $this->_makeName($obj_name);
		
		$this->$obj_name = $obj;
	}
	
	private function _makeName($object_name) {
		$class_short = str_replace(ARTISAN_, NULL, $class_name);
		$class_short = strtolower($class_short);
		return $class_short;
	}
}