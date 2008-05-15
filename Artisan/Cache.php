<?php


Artisan_Library::load('Database/Monitor');

abstract class Artisan_Database {

	public function __construct($config = array()) {
		echo 'In database constructor<br />';
		if ( count($config) > 0 ) {
			print_r($config);
		}
	}

	abstract public function connect();

	abstract public function disconnect();

	abstract public function query($sql);

	public function db() { echo 'in root db!<br />'; }
}

?>
