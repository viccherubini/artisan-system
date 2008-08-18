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

	
	public function build() {
		$distinct_sql = NULL;
		if ( true === $this->_distinct ) {
			$distinct_sql = " DISTINCT ";
		}
		
		$select_field_list = implode(", ", $this->_field_list);
		$select_sql  = "SELECT " . $distinct_sql . " " . $select_field_list . " FROM `" . $this->_from_table . "` ";
		$select_sql .= $this->_from_table_alias . " ";
		
		
		
		
		
		$where_sql = NULL;
		if ( count($this->_where_field_list) > 0 ) {
			$where_list = array();
			foreach ( $this->_where_field_list as $field => $value ) {
				$where_list[] = $field . " = '" . $this->escape($value) . "'";
			}
			$where_sql = " WHERE " . implode(" AND ", $where_list);
		}
		
		$group_sql = NULL;
		if ( count($this->_group_field_list) > 0 ) {
			$group_sql = " GROUP BY " . implode(", ", $this->_group_field_list);
		}
		
		
		
		$this->_sql = $select_sql . $where_sql . $group_sql;
		/*
		// First, begin to build the field list and initial select data.
		$field_list = $this->_field_list;
		$field_list = implode(', ', $field_list);
		
		$sql  = NULL;
		$sql .= 'SELECT ' . $field_list . ' ';
		$sql .= 'FROM `' . $this->_from_table . '` ' . $this->_from_table_alias . ' ';

		$join_sql = NULL;
		if ( true === $this->_has_join && count($this->_join_table_list) > 0 ) {
			foreach ( $this->_join_table_list as $table => $join ) {
				$join_sql .= ' ' . $join['type'] . ' `' . $table . '`';
				$join_sql .= ' ON ' . $join['field_a'] . ' = ' . $join['field_b'];
			}
		}
		
		$sql .= $join_sql;
		
		// Collect all of the where variables and escape them.
		// Build the return array.
		if ( count($this->_where_field_list) > 0 ) {
			$where_list = array();
			foreach ( $this->_where_field_list as $field => $value ) {
				$where_list[] = $field . " = '" . $this->escape($value) . "'";
			}
			$where_sql = implode(' AND ', $where_list);
			$sql .= ' WHERE ' . $where_sql;
		}
		
		$this->_sql = $sql;
		*/
	}
	
	
	public function query() {
		// Assume $this->_sql has not been built, so build it. If it
		// has been built, it'll simply be overwritten.
		$this->build();
		
		if ( false === empty($this->_sql) ) {
			$result = $this->CONN->query($this->_sql);

			if ( true === $result instanceof mysqli_result ) {
				$this->RESULT = $result;
			} else {
				throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
			}

			return $this;
		} else {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Query is empty', __CLASS__, __FUNCTION__);
		}
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
					if ( false === empty($field) && true === artisan_exists($field, $data) ) {
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
		$value = trim($value);
		return $this->CONN->real_escape_string($value);
	}
	
}

?>
