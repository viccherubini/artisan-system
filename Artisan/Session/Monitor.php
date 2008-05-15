<?php

class Artisan_Session_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Session $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Mysql
		if ( NULL === self::$instance ) {
			$db = Artisan_Library::load('Session/Database', true);
			if ( true === is_object($db) && $db instanceof Artisan_Session ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>
