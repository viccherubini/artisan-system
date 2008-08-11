<?php

/**
 * The Sql_Select class for creating a Select statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Sql_Select extends Artisan_Sql {
	///< The actual SQL query in string form.
	protected $_sql = NULL;
	
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The alias of the table the query is selecting FROM.
	protected $_from_table_alias = NULL;
	
	///< Contains a list of tables to join with. No aliases are stored, they are calculated at runtime.
	protected $_join_table_list = array();
	
	///< Whether or not this query contains a join statement, used in the on() method.
	protected $_has_join;
	
	///< The number of rows to return in a paginated query.
	protected $_return_row_count = 50;
	
	///< The list of fields to return from the query.
	protected $_field_list = array();
	
	///< The list of fields to use in the WHERE clause.
	protected $_where_field_list = array();
	
	///< The concatenation in the WHERE clause, either AND or OR.
	protected $_where_concat;
	
	///< The amount to use in a LIMIT clause. If 0, no LIMIT clause will be used.
	protected $_limit = 0;
	
	///< The page to return, if -1, all data will be returned without any pagination.
	protected $_page = -1;
	
	const SQL_JOIN_INNER = 'INNER JOIN';
	const SQL_JOIN_LEFT = 'LEFT JOIN';
	const SQL_JOIN_RIGHT = 'RIGHT JOIN';
	const SQL_JOIN = 'JOIN';
	
	public function __construct() {
		$this->_sql = NULL;
	}
	
	public function __destruct() {
		unset($this->_sql, $this->_from_table, $this->_from_table_list, $this->_field_list);
	}
	
	public function from($table, $fields = '*', $alias = NULL, $auto_alias = true) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$table = trim($table);
		$this->_from_table = $table;
		
		if ( true === empty($alias) && true === $auto_alias ) {
			$alias = artisan_create_table_alias($table);
		}
		
		$this->_from_table_alias = $alias;
		
		if ( '*' !== $fields ) {
			if ( false === is_array($fields) ) {
				$fields = array($fields);
			}
			
			$field_list = artisan_create_field_list($this->_from_table, $fields, $this->_from_table_alias);
			$this->_field_list = $field_list;
		} else {
			$this->_field_list = array('*');
		}
		
		return $this;
	}
	
	public function innerJoin($table, $field_a, $field_b) {
		$this->join(self::SQL_JOIN_INNER, $table, $field_a, $field_b);
		return $this;
	}
	
	public function leftJoin($table, $field_a, $field_b) {
		$this->join(self::SQL_JOIN_LEFT, $table, $field_a, $field_b);
		return $this;
	}
	
	public function join($join_type, $table, $field_a, $field_b) {
		$this->_has_join = true;
		
		$join = NULL;
		$table = trim($table);
		if ( false === artisan_exists($table, $this->_join_table_list) && $table != $this->_from_table ) {
			$this->_join_table_list[$table] = array(
				'table' => $table,
				'type' => $join_type,
				'field_a' => $field_a,
				'field_b' => $field_b
			);
		}
		
		return $this;
	}
	
	/*
	public function on($field_a, $field_b) {
		if ( true === $this->_has_join ) {
			
		}
	}
	*/
	
	public function where($where_fields) {
		if ( true === artisan_is_assoc($where_fields) ) {
			$this->_where_field_list = $where_fields;
		}
		
		return $this;
	}
	
	public function in($value_list) {
	
	}
	
	public function between($column, $value1, $value2) {
		return $this;
	}
	
	public function groupBy($fields) {
		return $this;
	}
	
	public function orderBy($fields, $type = 'ASC') {
		return $this;
	}
	
	public function limit($amount) {
		$this->_limit = intval(intval($amount));
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
		
		$this->_page = $page;
		//$this->_sql .= ' LIMIT ' . ($page * $this->_row_count) . ', ' . $this->_row_count;
		
		return $this;
	}
	
	//public function bind($field_data) {
		//$sql = parent::_where($this->_table, $this->_field_list, $field_data, $this->_where_type, $this->_alias);
		//$this->_sql .= $sql;
		
		//return $this;
	//}
	
	abstract public function query();
	
	abstract public function fetch($field = NULL);
	
	abstract public function fetchAll();
	
	abstract public function free();
	
	abstract public function escape($value);
	
	
	public function __toString() {
		return $this->_sql;
	}
	
	public function sql() {
		return $this->_sql;
	}
}

?>
