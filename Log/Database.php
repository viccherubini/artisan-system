<?php


class Artisan_Log_Database extends Artisan_Log {
	private $DB = NULL;
	
	/**
	 * Constructor for the Artisan_Log class to save logs to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @throws Artisan_Log_Exception If the database object passed into the class can not be connected to.
	 * @retval Object The new Artisan_Log_Database object.
	 */
	public function __construct(Artisan_Database &$db) {
		if ( false === $db->isConnected() ) {
			// Try to connect to the database
			try {
				$db->connect();
			} catch ( Artisan_Database_Exception $e ) {
				throw new Artisan_Log_Exception(ARTISAN_ERROR_CORE, $e->getMessage(), __CLASS__, __FUNCTION__);
			}
		}
		
		$this->DB = &$db;
	}
	

	public function flush() {
		if ( count($this->_log) > 0 ) {
			foreach ( $this->_log as $log ) {
				// See if this type can be inserted into the database as per the flush levels
				if ( true === in_array($log['log_type'], $this->_flush_level_list) ) {
					try {
						$this->DB->insert->into($this->_db_table, array_keys($log))->values($log)->query();
					} catch ( Artisan_Sql_Exception $e ) {
						// Can't do anything with $e
					}
				}
			}
		}
	}
}

?>
