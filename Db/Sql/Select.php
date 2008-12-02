<?php

require_once 'Artisan/Functions/Database.php';

/**
 * @see Artisan_Db_Sql
 */
require_once 'Artisan/Db/Sql.php';

/**
 * The Sql_Select class for creating a Select statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db_Sql_Select extends Artisan_Db_Sql {
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The alias of the table the query is selecting FROM.
	protected $_from_table_alias = NULL;
	
	///< Whether or not to include a DISTINCT clause.
	protected $_distinct = false;
	
	///< The list of fields to return in the query.
	protected $_field_list = array();

	///< Contains a list of tables to join with. No aliases are stored, they are calculated at runtime.
	protected $_join_table_list = array();
	
	///< The list of fields to use in the GROUP BY clause.
	protected $_group_field_list = array();
	
	///< Whether to sort ascending.
	protected $_asc = NULL;
	
	///< Or descending.
	protected $_desc = NULL;
	
	///< An INNER JOIN clause element.
	const SQL_JOIN_INNER = 'INNER JOIN';
	
	///< A LEFT JOIN clause element.
	const SQL_JOIN_LEFT  = 'LEFT JOIN';
	
	///< An OUTER JOIN clause element.
	const SQL_JOIN_OUTER = 'OUTER JOIN';
	
	///< A RIGHT JOIN clause element.
	const SQL_JOIN_RIGHT = 'RIGHT JOIN';
	
	/**
	 * Destructor, destroys the object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() {
		unset($this->_sql, $this->_from_table, $this->_from_table_list);
	}
	
	/**
	 * Starts building a SELECT FROM clause.
	 * @author vmc <vmc@leftnode.com>
	 * @param $table The name of thet able to select FROM.
	 * @param $alias Optional alias of a table.
	 * @param $N Each additional parameter will be the field name to select from, if none specified, * will be used.
	 * @retval Object Returns itself for chaining.
	 */
	public function from($table, $alias = NULL) {
		$this->_from_table = trim($table);
		$this->_from_table_alias = trim($alias);
		
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
			
			$this->_field_list = asfw_create_field_list($this->_from_table, $fields, NULL);
		} else {
			$this->_field_list = array('*');
		}
		
		return $this;
	}
	
	/**
	 * Adds a DISTINCT to the SELECT clause.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns itself for chaining.
	 */
	public function distinct() {
		$this->_distinct = true;
		return $this;
	}
	
	/**
	 * Creates an INNER JOIN clause.
	 * @author vmc <vmc@leftnode.com>
	 * @param $join_table The name of the table to join on.
	 * @param $field_a The field of the first table to join on.
	 * @param $field_b The field in $join_table to join on.
	 * @retval Object Returns intself for chaining.
	 */
	public function innerJoin($join_table, $field_a, $field_b) {
		$this->_join(self::SQL_JOIN_INNER, $join_table, $field_a, $field_b);
		return $this;
	}
	
	/**
	 * Creates an LEFT JOIN clause.
	 * @author vmc <vmc@leftnode.com>
	 * @param $join_table The name of the table to join on.
	 * @param $field_a The field of the first table to join on.
	 * @param $field_b The field in $join_table to join on.
	 * @retval Object Returns intself for chaining.
	 */
	public function leftJoin($join_table, $field_a, $field_b) {
		$this->_join(self::SQL_JOIN_LEFT, $join_table, $field_a, $field_b);
		return $this;
	}
	
	
	/**
	 * Adds a GROUP BY clause to the SELECT statement.
	 * @author vmc <vmc@leftnode.com>
	 * @param $N Each parameter adds one more field to the GROUP BY clause.
	 * @retval Object Returns itself for chaining.
	 */
	public function groupBy() {
		$group_fields = array();
		if ( func_num_args() > 0 ) {
			$group_fields = func_get_args();
		}
		
		if ( true === is_array($group_fields) && count($group_fields) > 0 ) {
			$this->_group_field_list = asfw_sanitize_field_list($group_fields);
		}
		
		return $this;
	}
	
	/**
	 * Starts building a SELECT FROM clause.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Finish implementing this!
	 * @retval Object Returns itself for chaining.
	 */
	public function orderBy() {
		$order_fields = array();
		if ( func_num_args() > 0 ) {
			$order_fields = func_get_args();
		}
		
		return $this;
	}

	/**
	 * Builds the SELECT SQL query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the built SQL.
	 */
	public function build() {
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
	 * Adds a JOIN to the SELECT clause.
	 * @author vmc <vmc@leftnode.com>
	 * @param $join_type The type of JOIN to make: LEFT, INNER, OUTER, RIGHT, etc.
	 * @param $join_table The name of the table to join on.
	 * @param $field_a The field from the first select table.
	 * @param $field_b The field from the joined table to compare.
	 * @todo Finish implementing this!
	 * @retval Object Returns itself for chaining.
	 */
	protected function _join($join_type, $join_table, $field_a, $field_b) {
		$join = array(
			'type' => $join_type,
			'table' => $join_table,
			'field_a' => $field_a,
			'field_b' => $field_b
		);
		
		$this->_join_table_list[] = $join;
		
		return true;
	}
}