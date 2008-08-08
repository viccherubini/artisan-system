<?php


Artisan_Library::load('Cache/Monitor');
Artisan_Library::load('Cache/Exception');

abstract class Artisan_Cache {

	public function __construct(Artisan_Config $config) {
	}
}

?>
