<?php

class Artisan_Template_Monitor {
	private static $instance = NULL;

	public static function set(Artisan_Template $t) {
		self::$instance = $t;
	}

	public static function get() {
		return self::$instance;
	}
}
