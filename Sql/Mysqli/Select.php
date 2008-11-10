<?php

/**
 * The Sql_Select class for creating a Select statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Sql_Select_Mysqli extends Artisan_Sql_Select {
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
	 * Builds the SELECT statement after all of the appropriate data has been collected.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the database is not of type mysqli.
	 * @retval string Returns the newly built SQL.
	 */
	public function build() {
		// Ensure that the connection actually exists before building the query.
		if ( false === $this->CONN instanceof mysqli ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The current connection to the database does not exist, no query can be built.', __CLASS__, __METHOD__);
		}
		
		$distinct_sql = NULL;
		if ( true === $this->_distinct ) {
			$distinct_sql = " DISTINCT ";
		}
		
		$select_field_list = implode(", ", $this->_field_list);
		$select_sql  = "SELECT " . $distinct_sql . " " . $select_field_list . " FROM `" . $this->_from_table . "` ";
		
		if ( false === empty($this->_from_table_alias) ) {
			$select_sql .= '`' . $this->_from_table_alias . '` ';
		}
		
		$join_sql = NULL;
		if ( count($this->_join_table_list) > 0 ) {
			foreach ( $this->_join_table_list as $join ) {
				$table_alias = asfw_create_table_alias($join['table']);
				$join_sql .= $join['type'] . ' `' . $join['table'] . '` ';
				$join_sql .= '`' . $table_alias . '` ';
				$join_sql .= 'ON ' . $join['field_a'] . ' = ' . $join['field_b'];
				$join_sql .= ' ';
			}
		}
		
		$where_sql = $this->buildWhereClause();
		
		$group_sql = NULL;
		if ( count($this->_group_field_list) > 0 ) {
			$group_sql = " GROUP BY " . implode(", ", $this->_group_field_list);
		}
		
		$this->_sql = $select_sql . $join_sql . $where_sql . $group_sql;
		
		return $this->_sql;
	}
	
	/**
	 * Execute the query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Sql_Exception If the SELECT query is empty.
	 * @throw Artisan_Sql_Exception If the query fails to execute.
	 * @retval Object Returns instance of itself for chainability.
	 */
	public function query() {
		// Assume $this->_sql has not been built, so build it. If it
		// has been built, it'll simply be overwritten.
		$this->build();

		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The SELECT query is empty, it can not be executed.', __CLASS__, __FUNCTION__);
		}

		$result = $this->CONN->query($this->_sql);

		if ( false === $result instanceof mysqli_result ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
		}

		$this->RESULT = $result;

		return $this;
	}
	
	/**
	 * Returns a row or single field from a query. If only one field of one row was queried, it will be returned.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field Optional parameter that if set, only that value is returned.
	 * @retval Mixed Returns either a row or a single value. Returns an empty array if no current database connection or no query has been executed.
	 */
	public function fetch($field = NULL) {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$data = $this->RESULT->fetch_assoc();
			if ( true === is_null($data) ) {
				$this->free();
			} else {
				reset($data);
				
				// Check if only one field was returned, if so, return that
				if ( 1 === count($data) ) {
					$data = current($data);
				} else {
					if ( false === empty($field) && true === asfw_exists($field, $data) ) {
						$data = $data[$field];				
					}
				}			
			}

			return $data;
		}
		
		return array();
	}
	
	/**
	 * Returns all records from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Finish implementing this, not yet implemented.
	 */
	public function fetchAll() {

	}
	
	/**
	 * Free's the latest connection to release memory.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function free() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$this->RESULT->free();
		}

		return true;
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
	 * Returns the number of rows selected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the number of rows affected by the SELECT.
	 */
	public function numRows() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			return $this->RESULT->num_rows;
		}
		
		return 0;
	}
}
