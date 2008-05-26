<?php

//$select = new select();
//$select->from

class Artisan_Sql_Select extends Artisan_Sql {
	private $_sql = NULL;
	
	private $_table = NULL;
	
	private $_alias = NULL;

	private $_row_count = 50;
	
	public function __construct() {
	
	}
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	
	public function from($table, $fields = '*', $alias = NULL) {
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
	
	public function where($fields, $type) {
		$sql = parent::_where($this->_table, $fields, $type, $this->_alias);
		
		$this->_sql .= $sql;
		
		return $this;
	}
	
	public function innerJoin($table, $conditions) {
		$this->_sql .= $this->_join('INNER', $table, $conditions);
		
		return $this;
	}
	
	public function leftJoin($table, $conditions) {
		$this->_sql .= $this->_join('LEFT', $table, $conditions);
		
		return $this;
	}
	
	private function _join($type, $table, $alias, $conditions) {
		$type = strtoupper($type);
		$alias = parent::createAlias($table);
		
		$sql_join = ' ' . $type . ' JOIN `' . $table . '` ' . $alias . ' ON ' . $conditions;
		
		return $sql_join;
	}
	
	public function between() {
	
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
	
	public function bind() {
	
	}
	
	public function query() {
	
	}
	
	public function fetchAll() {
	
	}
	
	public function fetchOne() {
	
	}
	
	public function __toString() {
		return $this->_sql;
	}
}

?>