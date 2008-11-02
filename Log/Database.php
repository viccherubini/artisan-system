<?php

/**
 * Stores log data in a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Log_Database extends Artisan_Log {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	///< The table name to insert log data into.
	const TABLE_LOG = 'artisan_log';
	
	/**
	 * Constructor for the Artisan_Log class to save logs to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB The database object to store data into, assumes it is already connected.
	 * @retval object The new Artisan_Log_Database object.
	 */
	public function __construct(Artisan_Database &$DB) {
		// We can only assume the database has a current connection
		// as we don't want to attempt to connect.
		$this->DB = &$DB;
	}
	
	/**
	 * Flush (or save) log data into the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function flush() {
		if ( count($this->_log) > 0 ) {
			foreach ( $this->_log as $log ) {
				// See if this type can be inserted into the database as per the flush levels
				if ( true === in_array($log['log_type'], $this->_flush_level_list) ) {
					try {
						$this->DB->insert->into(self::TABLE_LOG, array_keys($log))->values($log)->query();
					} catch ( Artisan_Sql_Exception $e ) {
						// Can't do anything with $e
					}
				}
			}
		}
		
		return true;
	}
}
