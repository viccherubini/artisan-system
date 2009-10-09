<?php

/**
 * @see Artisan_Log_Writer
 */
require_once 'Artisan/Log/Writer.php';

/**
 * This writer class writes log data into a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Log_Writer_Db extends Artisan_Log_Writer {
	///< Instance of a database to write the log data to.
	private $_dbConn = NULL;
	
	///< The name of the table to write to.
	private $_log_table = 'artisan_log';
	
	/**
	 * The default constructor for writing log data to a database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB An instance of a database object.
	 * @retval Object Returns new Artisan_Log_Writer_Db class instance.
	 */
	public function __construct(Artisan_Db $db) {
		$this->_dbConn = $db;
	}
	
	/**
	 * Sets the table to write the log data to.
	 * @author vmc <vmc@leftnode.com>
	 * @param $table_name The name of the table to write to.
	 * @retval boolean Returns true.
	 */
	public function setTable($table_name) {
		$table_name = trim($table_name);
		if ( false === empty($table_name) ) {
			$this->_log_table = $table_name;
		}
		return true;
	}
	
	/**
	 * Flushes the data out to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $log The log data to write.
	 * @retval boolean Returns true.
	 */
	public function flush($log) {
		if ( 0 === count($log) ) {
			return true;
		}

		try {
			$this->_dbConn->insert()
				->into($this->_log_table)
				->values($log)
				->query();
		} catch ( Artisan_Db_Exception $e ) {
			exit('Failed to write log entry.');
		}
		return true;
	}
}