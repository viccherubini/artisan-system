<?php

/**
 * The Sql_Select class for creating a Select statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Sql_Select_Mysqli extends Artisan_Sql_Select {
	private $CONN = NULL;
	private $RESULT = NULL;
	
	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}
	
	public function __destruct() { }
	
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
		
		$where_sql = $this->buildWhereClause();
		
		$group_sql = NULL;
		if ( count($this->_group_field_list) > 0 ) {
			$group_sql = " GROUP BY " . implode(", ", $this->_group_field_list);
		}
		
		
		
		$this->_sql = $select_sql . $where_sql . $group_sql;
	}
	
	
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
	
	public function fetchAll() {

	}
	
	public function free() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$this->RESULT->free();
		}

		return true;
	}
	
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
	
	public function numRows() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			return $this->RESULT->num_rows;
		}
		
		return 0;
	}
	
}
