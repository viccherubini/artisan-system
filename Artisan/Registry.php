<?php

require_once 'Artisan/Functions/Array.php';

class Artisan_Registry {
	private static $obj_list = array();

	public static function push($name, $obj, $force = false) {
		$name = trim($name);
		
		$found = asfw_exists($name, self::$obj_list);
		if ( (true === $found && true === $force) || false === $found ) {
			self::$obj_list[$name] = $obj;
		}
	}
	
	public static function pop($name, $remove = false) {
		$ret = NULL;
		if ( true === asfw_exists($name, self::$obj_list) ) {
			$ret = self::$obj_list[$name];
			
			if ( true === $remove ) {
				unset(self::$obj_list[$name]);
			}
		}
		
		return $ret;
	}
}