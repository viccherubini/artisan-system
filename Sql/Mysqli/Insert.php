<?php

/**
 * Inserts a query against a MySQL database after being built by chainable commands.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Sql_Insert_Mysqli extends Artisan_Sql_Insert {
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
	 * Builds the query to execute. If any of the values are one of the specified values
	 * defined in the switch below, they will be converted to their special value equivalent.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the connection is not the correct type (class mysqli).
	 * @retval boolean Returns true.
	 */
	public function build() {
		// Ensure that the connection actually exists before building the query.
		if ( false === $this->CONN instanceof mysqli ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The current connection to the database does not exist, no query can be built.', __CLASS__, __METHOD__);
		}
		
		$insert_sql  = "INSERT INTO `" . $this->_into_table . "`";
		
		$insert_field_sql = NULL;
		if ( count($this->_insert_field_list) > 0 ) {
			$insert_field_sql = " (" . implode(', ', $this->_insert_field_list) . ") ";
		}
		
		$value_list = array();
		$insert_value_sql = " VALUES (";
		foreach ( $this->_insert_field_value_list as $value ) {
			$value = $this->escape($value);
			switch ( strtoupper($value) ) {
				case NULL: {
					$value_list[] = 'NULL';
					break;
				}
				
				case 'NULL': 
				case 'NOW()': {
					$value_list[] = $value;
					break;
				}
			
				default: {
					$value_list[] = "'" . $value . "'";
					break;
				}
			}
			
		}
		$insert_value_sql = " VALUES (" . implode(", ", $value_list) . ") ";

		$this->_sql = $insert_sql . $insert_field_sql . $insert_value_sql;
		
		return true;
	}
	
	/**
	 * Executes the query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the SQL built is empty.
	 * @throw Artisan_Sql_Exception If the query fails to execute, the error string from the database is thrown.
	 * @retval Object Returns an instance of itself for chaining.
	 */
	public function query() {
		// Assume $this->_sql has not been built, so build it. If it
		// has been built, it'll simply be overwritten.
		$this->build();
		
		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The INSERT query is empty, it can not be executed.', __CLASS__, __FUNCTION__);
		}
		
		$result = $this->CONN->query($this->_sql);
		
		if ( false === $result ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
		}
		
		return $this;
	}
	
	/**
	 * Returns the number of rows inserted.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the number of rows affected by the INSERT.
	 */
	public function affectedRows() {
		return $this->CONN->affected_rows;
	}
	
	/**
	 * Escapes a string based on the character set of the current connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns a context escaped string.
	 */
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
}

?>
