<?php

class Artisan_Email_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Email $e) {
		self::$instance = $e;
	}

	public static function get() {
		return self::$instance;
	}

}

?>
