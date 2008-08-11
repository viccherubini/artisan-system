<?php


Artisan_Library::load('Cache/Monitor');
Artisan_Library::load('Cache/Exception');

/**
 * The Artisan_Cache class allows one to store data in an easy to retrieve location,
 * typically a location that is quicker to retrieve data from than the original 
 * location.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Cache {

	public function __construct(Artisan_Config $config) {
	}
	
	public function __destruct() {
	
	}
}

?>
