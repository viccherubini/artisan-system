<?php

/**
 * Deletes values from a MySQL database after being built by chainable commands.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Sql_Delete_Mysqli extends Artisan_Sql_Delete {
	///< If true, after the delete is executed, the table will be optimized to clean up fragmentation.
	private $_auto_optimize = false;
	
	///< The connection object to the database, assumes the database is already connected.
	private $CONN = NULL;
	
	/**
	 * Builds a new object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CONN A database connection object of type mysqli. Assumes the database is already connected.
	 * @retval Object A new instance of this class.
	 */
	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}
	
	/**
	 * Destroys this object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing, destroys the object.
	 */
	public function __destruct() {
	}

	/**
	 * Builds the query to execute.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the connection is not the correct type (class mysqli).
	 * @retval boolean Returns true.
	 */
	public function build() {
		// Ensure that the connection actually exists before building the query.
		if ( false === $this->CONN instanceof mysqli ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The current connection to the database does not exist, no query can be built.', __CLASS__, __METHOD__);
		}
		
		$where_sql = $this->buildWhereClause($this->_where_field_list);
		
		$limit_sql = NULL;
		if ( $this->_limit > 0 ) {
			$limit_sql = " LIMIT " . $this->_limit;
		}
		
		$delete_start = "DELETE FROM `" . $this->_from_table . "` ";
		$this->_sql = $delete_start . $where_sql . $limit_sql;
	}

	/**
	 * Executes the query against the database. If $_auto_optimize is true, and the
	 * query successfully executed, the table is optimized clean up fragmentation.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the SQL built is empty.
	 * @throw Artisan_Sql_Exception If the query fails to execute, the error string from the database is thrown.
	 * @retval Object Returns an instance of itself for chaining.
	 */
	public function query() {
		$this->build();
		
		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The DELETE query is empty, it can not be executed.', __CLASS__, __FUNCTION__);
		}
		
		$result = $this->CONN->query($this->_sql);
		
		if ( false === $result ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
		}
		
		if ( true === $this->_auto_optimize ) {
			$this->optimize();
		}
		
		return $this;
	}
	
	/**
	 * Escapes a string based on the character set of the current connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a context escaped string.
	 */
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
	
	/**
	 * Turns on auto-optimizing.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function autoOptimize() {
		$this->_auto_optimize = true;
		return true;
	}
	
	/**
	 * Optimizes the previously altered table.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function optimize() {
		$this->CONN->query('OPTIMIZE TABLE `' . $this->_from_table);
		return true;
	}
	
	/**
	 * Returns the number of affected rows from the query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Number of rows deleted.
	 */
	public function affectedRows() {
		return $this->CONN->affected_rows;
	}
}
