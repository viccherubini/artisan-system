<?php

class Artisan_Log_Monitor {

	private static $instance = NULL;
	
	public static function set(Artisan_Log $db) {
		self::$instance = $db;
	}

	public static function get() {
		if ( NULL === self::$instance ) {
			return NULL;
		}
		
		return self::$instance;
	}
}
