<?php

define('ARTISAN_NAME', 'Artisan');

// Load up the exception so it's always included.
Artisan_Library::load('Exception');

// Load in the function libraries
Artisan_Library::load('Functions/Array');
Artisan_Library::load('Functions/Database');
Artisan_Library::load('Functions/Encryption');
Artisan_Library::load('Functions/Html');
Artisan_Library::load('Functions/Input');
Artisan_Library::load('Functions/String');
Artisan_Library::load('Functions/System');

/**
 * This class allows you to easily load and manage other classes in Artisan.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Library {

	///< The list of Artisan objects currently loaded.
	private static $object_list = array();

	/**
	 * Loads in an Artisan library.
	 * @author vmc <vmc@leftnode.com>
	 * @param $lib_name The name or path of the library to load, examples are Database, or Database/Mysqli.
	 * @param $build If a single class is included, and $build is true, the class will be created.
	 * @param $system_class If true, loads only Artisan classes, otherwise, allows one to load external classes.
	 * @retval boolean Returns true if the class was loaded, false otherwise.
	 * @todo Allow more than one level of loading.
	 */
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
		return self::_load($lib_name, $build, $system_class);
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