<?php

class Artisan_Template_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Template $t) {
		self::$instance = $t;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Database 
		if ( NULL === self::$instance ) {
			$t = Artisan_Library::load('Template/Database', true);
			if ( true === is_object($t) && $t instanceof Artisan_Template ) {
				self::set($t);
			}
		}

		return self::$instance;
	}

}

?>
