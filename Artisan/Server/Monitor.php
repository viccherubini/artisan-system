<?php

class Artisan_Server_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Server $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Mysql
		if ( NULL === self::$instance ) {
			$db = Artisan_Library::load('Server/Curl', true);
			if ( true === is_object($db) && $db instanceof Artisan_Server ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>
