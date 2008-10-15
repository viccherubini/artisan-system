<?php

class Artisan_Auth_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Auth $auth) {
		self::$instance = $auth;
	}

	public static function get() {
		return self::$instance;
	}

}
