<?php

class Artisan_User_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_User $e) {
		self::$instance = $e;
	}

	public static function get() {
		return self::$instance;
	}

}

?>
