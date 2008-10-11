<?php

class Artisan_Sql_Delete_Mysqli extends Artisan_Sql_Delete {
	private $_auto_optimize = false;
	
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
		
		$where_sql = NULL;
		if ( count($this->_where_field_list) > 0 ) {
			$where_list = array();
			foreach ( $this->_where_field_list as $field => $value ) {
				$where_list[] = $field . " = '" . $this->escape($value) . "'";
			}
			$where_sql = " WHERE " . implode(" AND ", $where_list);
		}
		
		$limit_sql = NULL;
		if ( $this->_limit > 0 ) {
			$limit_sql = " LIMIT " . $this->_limit;
		}
		
		
		$delete_start = "DELETE FROM `" . $this->_from_table . "` ";
		$this->_sql = $delete_start . $where_sql . $limit_sql;
	}

	public function query() {
		$this->build();
		
		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The DELETE query is empty, it can not be executed.', __CLASS__, __FUNCTION__);
		}
		
		$result = $this->CONN->query($this->_sql);
		
		if ( false === $result ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to execute the DELETE query: ' . $this->_sql, __CLASS__, __FUNCTION__);
		}
		
		if ( true === $this->_auto_optimize ) {
			$this->optimize();
		}
		
		return $this;
	}
	
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
	
	public function autoOptimize() {
		$this->_auto_optimize = true;
	}
	
	public function optimize() {
		$this->CONN->query('OPTIMIZE TABLE `' . $this->_from_table);
	}
	
	public function affectedRows() {
		return $this->CONN->affected_rows;
	}
}
