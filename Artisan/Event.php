<?php

require_once 'Artisan/Event/Exception.php';

class Artisan_Event {
	private $_dbConn;

	

	/**
	 * Ensures that a database connection exists.
	 * @author vmc <vmc@leftnode.com>
	 * @param $method The method this is being called from.
	 * @throw Artisan_User_Exception If the database connection does not exist.
	 * @retval boolean Returns true.
	 */
	private function _checkDb() {
		if ( false === $this->DB->isConnected() ) {
			throw new Artisan_Event_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
}