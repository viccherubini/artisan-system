<?php

class Artisan_Database_Monitor {

	private static $instance = NULL;
	
	public static function set(Artisan_Database $db) {
		self::$instance = $db;
	}

	public static function get(Artisan_Config $config = NULL) {
		// Example of the Lazy Initialization pattern, default to use Mysql
		if ( NULL === self::$instance || false === is_null($config) ) {
			// Determine if a type is set in the configuration
			$type = 'Mysql';
			if ( true === $config->exists('type') ) {
				$type = ucwords($config->type);
			}
		
			$db = Artisan_Library::load('Database/' . $type, true);
			
			
			if ( true === is_object($db) && $db instanceof Artisan_Database ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>