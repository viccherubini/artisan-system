<?php

/**
 * Updated a table against a MySQL database after being built by chainable commands.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Sql_Update_Mysqli extends Artisan_Sql_Update {
	///< The database connection object, assumes the database is already connected.
	private $CONN = NULL;
	
	///< The result object after executing a query.
	private $RESULT = NULL;
	
	/**
	 * Default constructor, builds the Update class.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CONN The database connection object.
	 * @retval Object Returns new Artisan_Sql_Update_Mysqli object.
	 */
	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}
	
	/**
	 * Destroys the object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() { }
	
	/**
	 * Builds the UPDATE query.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the database is not of type mysqli.
	 * @retval string Returns the built SQL.
	 */
	public function build() {
		// Ensure that the connection actually exists before building the query.
		if ( false === $this->CONN instanceof mysqli ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The current connection to the database does not exist, no query can be built.', __CLASS__, __METHOD__);
		}
		
		$update_sql = "UPDATE `" . $this->_table . "` ";
		
		$fl_len = count($this->_update_field_list)-1;
		$i=0;
		$field_list_sql = " SET ";
		foreach ( $this->_update_field_list as $field => $value ) {
			if ( '`' == $value[0] ) {
				$field_list_sql .= $field . " = " . $this->escape($value);
			} else {
				$field_list_sql .= $field . " = '" . $this->escape($value) . "'";
			}
			if ( $i != $fl_len ) {
				$field_list_sql .= ', ';
			}
			$i++;
		}
		
		$where_sql = $this->buildWhereClause();
		
		$this->_sql = $update_sql . $field_list_sql . $where_sql;
		
		return $this->_sql;
	}
	
	/**
	 * Execute the query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the UPDATE query is empty.
	 * @retval Object Returns instance of itself for chainability.
	 */
	public function query() {
		// Assume $this->_sql has not been built, so build it. If it
		// has been built, it'll simply be overwritten.
		$this->build();

		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The UPDATE query is empty, it can not be executed.', __CLASS__, __FUNCTION__);
		}

		$result = $this->CONN->query($this->_sql);
		$this->RESULT = $result;

		return $this;
	}
	
	/**
	 * Returns the number of rows updated.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the number of rows affected by the UPDATE.
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
