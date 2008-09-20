<?php

Artisan_Library::load('Session/Monitor');
Artisan_Library::load('Session/Exception');

abstract class Artisan_Session {

	public function __construct(Artisan_Config &$config) {
	
	}
}

?>
