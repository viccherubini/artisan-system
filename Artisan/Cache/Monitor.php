<?php

class Artisan_Database_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Database $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Mysql
		if ( NULL === self::$instance ) {
			$db = Artisan_Library::load('Database/Mysql', true);
			if ( true === is_object($db) && $db instanceof Artisan_Database ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>
