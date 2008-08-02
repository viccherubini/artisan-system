<?php



class Artisan_Email_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Email $db) {
		self::$instance = $db;
	}

	public static function get() {
		// Because the email may require configuration, return nothing
		// if it doesn't exist.
		return self::$instance;
	}

}

?>
