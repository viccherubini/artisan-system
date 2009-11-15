<?php

require_once 'Func.Library.php';

class Artisan_Registry {
	private static $initialized = false;
	private static $object_list = array();
	private static $model_list = array();
	
	public static function push($name, $item, $overwrite=true) {
		$exists = exs($name, self::$object_list);
		if ( false === $exists || ( true === $exists && true === $overwrite ) ) {
			self::$object_list[$name] = $item;
		}
		return true;
	}
	
	public static function pop($name, $delete=false) {
		$item = er($name, self::$object_list);
		if ( true === $delete ) {
			unset(self::$object_list[$name]);
		}
		return $item;
	}
}