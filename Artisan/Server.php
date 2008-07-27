<?php

Artisan_Library::load('Server/Monitor');
Artisan_Library::load('Server/Exception');

abstract class Artisan_Server {

	public function __construct($config = array()) {
	}
}

?>