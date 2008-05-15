<?php

class Artisan_Auth_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Auth $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Mysql
		if ( NULL === self::$instance ) {
			$db = Artisan_Library::load('Auth/Database', true);
			if ( true === is_object($db) && $db instanceof Artisan_Auth ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>
