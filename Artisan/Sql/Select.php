<?php

class Artisan_Sql_Select extends Artisan_Sql {
	private $_sql = NULL;
	private $_table = NULL;
	private $_alias = NULL;
	private $_row_count = 50;
	private $_field_list = array();
	private $_where_type = 'AND';
	private $_has_join = false;
	
	const SQL_JOIN_INNER = 'INNER JOIN';
	const SQL_JOIN_LEFT = 'LEFT JOIN';
	const SQL_JOIN = 'JOIN';
	
	public function __construct() {
	
	}
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	
	public function from($table, $fields = '*', $alias = NULL) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_table = $table;
		
		if ( true === empty($alias) ) {
			$alias = parent::createAlias($table);
		}
		
		$this->_alias = $alias;
		
		$field_list = $fields;
		if ( '*' !== $fields ) {
			if ( false === is_array($fields) ) {
				$fields = array($fields);
			}
			
			$field_list = parent::createFieldList($table, $fields, $alias);
			$field_list = implode(', ', $field_list);
		}
		
		$this->_sql = 'SELECT ' . $field_list . ' FROM `' . $table . '` ' . $alias;
		
		return $this;
	}
	
	public function where($fields, $type = 'AND') {
		$this->_field_list = $fields;
		$this->_where_type = $type;
		
		return $this;
	}
	
	public function innerJoin($table) {
		$this->_sql .= $this->_join('INNER', $table, $conditions);
		
		return $this;
	}
	
	public function leftJoin($table) {
		$this->_sql .= $this->_join('LEFT', $table, $conditions);
		
		return $this;
	}
	
	public function join($table) {
		
	}
	
	/*
	private function _join($type, $table, $alias, $conditions) {
		$type = strtoupper($type);
		$alias = parent::createAlias($table);
		
		//$sql_join = ' ' . $type . ' JOIN `' . $table . '` ' . $alias . ' ON ' . $conditions;
		
		$this->_has_join = true;
		return $sql_join;
	}
	*/
	
	public function on($field_a, $field_b) {
		if ( true === $this->_has_join ) {
			
		}
	}
	
	public function between($column, $value1, $value2) {
		//$this->_sql .= parent::_where($this->_table, array($column), 
	}
	
	public function groupBy($fields) {
		$fields = parent::createFieldList($this->_table, $fields);
		$this->_sql .= ' GROUP BY ' . implode(', ', $fields);
		
		return $this;
	}
	
	public function orderBy($fields, $type = 'ASC') {
		if ( false === is_array($fields) ) {
			$fields = array($fields);
		}
		
		$fields = parent::createFieldList($this->_table, $fields, $this->_alias);
		
		$type = strtoupper($type);
		if ( $type !== 'DESC' || $type !== 'ASC' ) {
			$type = 'ASC';
		}
		
		$this->_sql .= ' ORDER BY ' . implode(', ', $fields) . ' ' . $type;
		
		return $this;
	}
	
	public function limit($amount) {
		$amount = intval($amount);
		
		if ( $amount < 0 ) {
			$amount = 0;
		}
		
		$this->_sql .= ' LIMIT ' . $amount;
		
		return $this;
	}
	
	public function setPaging($amount) {
		$amount = intval($amount);
		
		if ( $amount < 1 ) {
			$amount = 1;
		}
		
		$this->_row_count = $amount;
		
		return $this;
	}
	
	public function page($page) {
		$page = intval($page);
		
		if ( $page < 1 ) {
			$page = 1;
		}
		
		$page--;
		
		$this->_sql .= ' LIMIT ' . ($page * $this->_row_count) . ', ' . $this->_row_count;
		
		return $this;
	}
	
	public function bind($field_data) {
		$sql = parent::_where($this->_table, $this->_field_list, $field_data, $this->_where_type, $this->_alias);
		$this->_sql .= $sql;
		
		return $this;
	}
	
	/*
	public function query() {
		if ( true === Artisan_Library::exists('Database') ) {
			$db = Artisan_Database_Monitor::get();
			
			if ( true === is_object($db) ) {
				$result = $db->query($this);
				return $result;
			}
		}
		
		return NULL;
	}
	
	public function fetchAll() {
		$data = array();
		if ( true === Artisan_Library::exists('Database') ) {
			$db = Artisan_Database_Monitor::get();
			
			if ( true === is_object($db) ) {
				try {
					$db->query($this);
				
					while ( $r = $db->fetch() ) {
						$data[] = $r;
					}
				} catch ( Artisan_Database_Exception $e ) {
					throw new Artisan_Sql_Exception(
						ARTISAN_WARNING, $e->getMessage(),
						__CLASS__, __FUNCTION__
					);
				}
			}
		}
		
		return $data;
	}
	
	public function fetchOne() {
		$data = array();
		if ( true === Artisan_Library::exists('Database') ) {
			$db = Artisan_Database_Monitor::get();
			if ( true === is_object($db) ) {
				$this->query();
			
				if ( $db->rowCount() > 0 ) {
					$data = $db->fetch();
				}
			}
		}
		
		return $data;
	}
	*/
	
	public function __toString() {
		return $this->_sql;
	}
	
	public function sql() {
		return $this->_sql;
	}
}

?>