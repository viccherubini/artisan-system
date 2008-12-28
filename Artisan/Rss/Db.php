<?php

/**
 * @see Artisan_Rss
 */
require_once 'Artisan/Rss.php';

/**
 * Loads data for an RSS feed from a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Rss_Db extends Artisan_Rss {
	///< The Database instance, must have an active connection.
	protected $DB = NULL;
	
	/**
	 * Builds a new object to load in RSS data from a database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB The Database instance, must have an active connection.
	 * @retval Object A new Artisan_Rss_Db object.
	 */
	public function __construct(Artisan_Db &$DB) {
		$this->DB = &$DB;
	}
	
	/**
	 * Loads up the RSS data from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function load() {
	
	}
}