<?php

require_once 'Sql.php';

class Artisan_Select extends Artisan_Sql {
	protected $from_table = NULL;
	protected $from_table_alias = NULL;
	protected $distinct = false;
	protected $field_list = array();
	protected $join_table_list = array();
	protected $group_field_list = array();
	protected $order_list;
	protected $order_method;
	protected $asc = NULL;
	protected $desc = NULL;
	protected $limit = 0;
	protected $page = -1;
	
	const SQL_JOIN_INNER = 'INNER JOIN';
	const SQL_JOIN_LEFT  = 'LEFT JOIN';
	const SQL_JOIN_OUTER = 'OUTER JOIN';
	const SQL_JOIN_RIGHT = 'RIGHT JOIN';
	
	public function __destruct() {
		unset($this->sql, $this->from_table, $this->from_table_list);
	}
	
	public function from($table, $alias = NULL) {
		$this->from_table = trim($table);
		$this->from_table_alias = trim($alias);
		
		// Rather than having the customer supply an array, allow them
		// to supply as many parameters as they want for all of the fields.
		$fields = '*';
		$arg_length = func_num_args();
		if ( $arg_length > 2 ) {
			$args = func_get_args();
			$arg_len = count($args);
			
			// If the arg_len is equal to 3, and the third 
			// parameter is an array, use that, otherwise, splice them up
			if ( 3 === $arg_len && true === is_array($args[2]) ) {
				$fields = $args[2];
			} else {
				$fields = array_splice($args, 2, $arg_len, array());
			}
		}
		
		if ( '*' !== $fields ) {
			if ( false === is_array($fields) ) {
				$fields = array($fields);
			}
			$this->field_list = create_field_list($this->from_table, $fields, NULL);
		} else {
			$this->field_list = array('*');
		}
		return $this;
	}
	
	public function distinct() {
		$this->distinct = true;
		return $this;
	}
	
	public function innerJoin($join_table, $field_a, $field_b) {
		$this->join(self::SQL_JOIN_INNER, $join_table, $field_a, $field_b);
		return $this;
	}
	
	public function leftJoin($join_table, $field_a, $field_b) {
		$this->join(self::SQL_JOIN_LEFT, $join_table, $field_a, $field_b);
		return $this;
	}
	
	public function groupBy() {
		$group_fields = array();
		if ( func_num_args() > 0 ) {
			$group_fields = func_get_args();
		}
		if ( true === is_array($group_fields) && count($group_fields) > 0 ) {
			$this->group_field_list = sanitize_field_list($group_fields);
		}
		return $this;
	}
	
	public function orderBy($field, $method = 'ASC') {
		if ( func_num_args() > 0 ) {
			$this->order_list[] = $field;
			$this->order_method = $method;
		}
		return $this;
	}

	public function limit($length) {
		$length = abs($length);
		if ( $length > 0 ) {
			$this->limit = $length;
		}
		return $this;
	}
	
	public function page($page) {
		$page = abs($page);
		$this->page = $page;
		return $this;
	}

	public function build() {
		$distinct_sql = NULL;
		if ( true === $this->distinct ) {
			$distinct_sql = " DISTINCT ";
		}
		
		$select_field_list = implode(", ", $this->field_list);
		$select_sql  = "SELECT " . $distinct_sql . " " . $select_field_list . " FROM `" . $this->from_table . "` ";
		
		if ( false === empty($this->from_table_alias) ) {
			$select_sql .= '`' . $this->from_table_alias . '` ';
		}
		
		$join_sql = NULL;
		if ( count($this->join_table_list) > 0 ) {
			foreach ( $this->join_table_list as $join ) {
				$table_alias = create_table_alias($join['table']);
				$join_sql .= $join['type'] . ' `' . $join['table'] . '` ';
				$join_sql .= '`' . $table_alias . '` ';
				$join_sql .= 'ON ' . $join['field_a'] . ' = ' . $join['field_b'];
				$join_sql .= ' ';
			}
		}
		
		$where_sql = $this->buildWhereClause();
		
		$order_sql = NULL;
		if ( count($this->order_list) > 0 ) {
			$order_sql = " ORDER BY " . implode(', ', $this->order_list) . " " . strtoupper($this->order_method);
		}
		
		$group_sql = NULL;
		if ( count($this->group_field_list) > 0 ) {
			$group_sql = " GROUP BY " . implode(", ", $this->group_field_list);
		}
		
		$limit_sql = NULL;
		if ( $this->limit > 0 ) {
			$page_sql = NULL;
			if ( $this->page > -1 ) {
				$page_sql = $this->page * $this->limit . ", ";
			}
			
			$limit_sql = " LIMIT " . $page_sql . $this->limit;
		}
		
		$this->sql = $select_sql . $join_sql . $where_sql . $group_sql . $order_sql . $limit_sql;
		$this->group_field_list = $this->order_list = array();
		$this->order_method = NULL;
		$this->join_table_list = array();
		$this->limit = 0;
		
		return $this->sql;
	}

	protected function join($join_type, $join_table, $field_a, $field_b) {
		$join = array(
			'type' => $join_type,
			'table' => $join_table,
			'field_a' => $field_a,
			'field_b' => $field_b
		);
		
		$this->join_table_list[] = $join;
		return true;
	}
}