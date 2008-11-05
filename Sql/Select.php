<?php

/**
 * The Sql_Select class for creating a Select statement to run against a database.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Sql_Select extends Artisan_Sql {
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
	
	/**
	 * Builds a new SELECT clause.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns new Artisan_Sql_Select object.
	 */
	public function __construct() {
		$this->_sql = NULL;
	}
	
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
	 * @throw Artisan_Sql_Exception If the table name is empty.
	 * @retval Object Returns itself for chaining.
	 */
	public function from($table, $alias = NULL) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL SELECT class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
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
	 * Adds a JOIN to the SELECT clause.
	 * @author vmc <vmc@leftnode.com>
	 * @param $join_type The type of JOIN to make: LEFT, INNER, OUTER, RIGHT, etc.
	 * @param $join_table The name of the table to join on.
	 * @param $field_a The field from the first select table.
	 * @param $field_b The field from the joined table to compare.
	 * @todo Finish implementing this!
	 * @retval Object Returns itself for chaining.
	 */
	public function join($join_type, $join_table, $field_a, $field_b) {
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
	 * Abstract method to build the SQL query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the built SQL.
	 */
	abstract public function build();
	
	/**
	 * Abstract method to execute the query after it's built.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns itself for chaining.
	 */
	abstract public function query();
	
	/**
	 * Abstract method to fetch a single row or single field if available.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field The optional single field to return.
	 * @retval Mixed Returns the single row or single field.
	 */
	abstract public function fetch($field = NULL);
	
	/**
	 * Abstract method to return all available rows from the SQL.
	 * @author vmc <vmc@leftnode.com>
	 * @retval array Returns an array of all available rows.
	 */
	abstract public function fetchAll();
	
	/**
	 * Abstract method to free the result.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function free();
	
	/**
	 * Abstract method to escape a string before being inserted into the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $value The value to escape.
	 * @retval string Returns the escaped value.
	 */
	abstract public function escape($value);
}
