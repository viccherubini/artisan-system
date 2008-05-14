<?php

define('ARTISAN_NAME', 'Artisan');

class Artisan_Library {

	protected static $object_list = array();

	public static function load($lib_name, $build = false) {
		$class = ARTISAN_NAME . '_' . str_replace('/', '_', $lib_name);

		$class_exists = class_exists($class, false);
		$interface_exists = interface_exists($class, false);

		$ext = '.php';

		if ( false === in_array($lib_name, self::$object_list) && false === $class_exists && false === $interface_exists ) {
			$abs_path = dirname(__FILE__);

			$file = $abs_path . '/' . $lib_name . $ext;

			if ( true === file_exists($file) && true === is_file($file) ) {
				require_once $file;

				self::$object_list[] = $lib_name;

				if ( true === $build && true === class_exists($class) ) {
					$instance = new $class;

					return $instance;
				}
				
				return true;
			}
		}

		return false;
	}

}

?>
