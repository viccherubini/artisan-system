<?php

class Artisan_Cache_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Cache $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Mysql
		if ( NULL === self::$instance ) {
			$db = Artisan_Library::load('Cache/Database', true);
			if ( true === is_object($db) && $db instanceof Artisan_Cache ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>
