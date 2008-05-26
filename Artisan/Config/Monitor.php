<?php

class Artisan_Config_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Config $cfg) {
		self::$instance = $cfg;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Array
		if ( NULL === self::$instance ) {
			$cfg = Artisan_Library::load('Config/Array', true);
			if ( true === is_object($cfg) && $cfg instanceof Artisan_Config ) {
				self::set($cfg);
			}
		}

		return self::$instance;
	}

}

?>
