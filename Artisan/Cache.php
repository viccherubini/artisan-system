<?php


Artisan_Library::load('Cache/Exception');

/**
 * The Artisan_Cache class allows one to store data in an easy to retrieve location,
 * typically a location that is quicker to retrieve data from than the original 
 * location.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Cache {

	/**
	 * Constructor to build a cache object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C Configuration object.
	 * @retval Object Returns new Artisan_Cache object.
	 */
	public function __construct(Artisan_Config &$C) { }
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() { }
}
