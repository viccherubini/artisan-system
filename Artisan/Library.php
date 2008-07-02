<?php

define('ARTISAN_NAME', 'Artisan');

class Artisan_Library {

	private static $object_list = array();

	public static function load($lib_name, $build = false, $system_class = true) {
		// First, see if this is a concrete class, and if so,
		// attempt to include it's parent class.
		if ( false !== strpos($lib_name, '/') ) {
			$classes = explode('/', $lib_name);

			// Get the first element, which is the parent class
			if ( false === self::exists($classes[0]) ) {
				self::_load($classes[0], $build, $system_class);
			}
		}

		// Now, load up the child class
		self::_load($lib_name, $build, $system_class);
	}

	private static function _load($lib_name, $build = false, $system_class = true ) {
		$class_prefix = NULL;
		if ( true === $system_class ) {
			$class_prefix = ARTISAN_NAME . '_';
		}

		$class = $class_prefix . str_replace('/', '_', $lib_name);

		$class_exists = class_exists($class, false);
		$interface_exists = interface_exists($class, false);

		$ext = '.php';

		if ( false === in_array($lib_name, self::$object_list) && false === $class_exists && false === $interface_exists ) {
			$abs_path = dirname(__FILE__);

			$file = $abs_path . '/' . $lib_name . $ext;

			if ( true === file_exists($file) && true === is_file($file) ) {
				/**
				 * IMPORTANT!
				 * Adding the library name before including the library
				 * means that if a child class is included and the parent
				 * is automatically included, and the parent includes children
				 * classes, this method won't attempt to add the parent class
				 * to the library because it's already been added.
				 */
				self::$object_list[] = $lib_name;

				require_once $file;

				if ( true === $build && true === class_exists($class) ) {
					$instance = new $class;

					return $instance;
				}
				
				return true;
			}
		}

		return false;
	}

	public static function exists($lib_name) {
		return in_array($lib_name, self::$object_list);
	}
	
	public static function getObjectList() {
		return self::$object_list;
	}
}

?>
