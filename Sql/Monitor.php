<?php

class Artisan_Sql_Monitor {

	private static $instance = NULL;

	public static function set(Artisan_Sql $sql) {
		self::$instance = $sql;
	}

	public static function get() {
		// Example of the Lazy Initialization pattern, default to use Select
		if ( NULL === self::$instance ) {
			$sql = Artisan_Library::load('Sql/Select', true);
			if ( true === is_object($sql) && $sql instanceof Artisan_Sql ) {
				self::set($sql);
			}
		}

		return self::$instance;
	}

}
