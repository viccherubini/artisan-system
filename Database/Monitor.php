<?php

class Artisan_Database_Monitor {

	private static $instance = NULL;
	
	public static function set(Artisan_Database $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Because of the unknown status of any database connections,
		// there are no default database types to use.
		if ( NULL === self::$instance ) {
			return NULL;
		}
		
		return self::$instance;
	}

}

?>