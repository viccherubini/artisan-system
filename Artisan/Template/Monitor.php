<?php

class Artisan_Template_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Template $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Database 
		if ( NULL === self::$instance ) {
			$db = Artisan_Library::load('Template/Database', true);
			if ( true === is_object($db) && $db instanceof Artisan_Template ) {
				self::set($db);
			}
		}

		return self::$instance;
	}

}

?>
