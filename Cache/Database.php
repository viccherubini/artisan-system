<?php

/**
 * @see Artisan_Cache
 */
require_once 'Artisan/Cache.php';

/**
 * Loads cached data from the database.
 * @author vmc <vmc@leftnode.com>
 * @todo Implement this!
 */
class Artisan_Cache_Db extends Artisan_Cache {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	/**
	 * Constructor for the Artisan_Cache class to save logs to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB The database object to store data into, assumes it is already connected.
	 * @retval object The new Artisan_Cache_Database object.
	 */
	public function __construct(Artisan_Db &$DB) {
		// We can only assume the database has a current connection
		// as we don't want to attempt to connect.
		$this->DB = &$DB;
	}
}