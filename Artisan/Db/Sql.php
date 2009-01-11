<?php

/**
 * This class allows the creation of a new Sql object to build
 * queries through chainable commands. It supports SELECT, UPDATE,
 * INSERT, REPLACE, DELETE, and GENERAL.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db_Sql {
	///< The database object or resource.
	protected $DB = NULL;
	
	///< The SQL that will be built.
	protected $_sql = NULL;
	
	///< The list of fields to use in the WHERE clause.
	protected $_where_field_list = array();
	
	///< The SQL keyword AND
	const SQL_AND = 'AND';
	
	///< The SQL keyword OR
	const SQL_OR = 'OR';
	
	/**
	 * Default constructor for building a new SQL query. This class is abstract and
	 * can't be built directly.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Db_Sql object.
	 */
	public function __construct(Artisan_Db &$DB) {
		$this->DB = &$DB;
	}
	
	/**
	 * Destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
	}
	
	/**
	 * Creates an AND clause in a WHERE clause.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field_op Parameters are not specified, first one is the operation of the field
	 * @param $value The value to be replaced if there is a ? in the $field_op
	 * @param $valueN You can specify as many values as there are ?'s in the $field_op.
	 * @retval Object Returns itself for chainability.
	 */
	public function where() {
		$argv = func_get_args();
		$this->_where(self::SQL_AND, $argv);
		return $this;
	}
	
	/**
	 * Not yet implemented, but creates an OR clause in a WHERE clause.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Finish implementing this and take out stub programming.
	 * @param $field_op Parameters are not specified, first one is the operation of the field
	 * @param $value The value to be replaced if there is a ? in the $field_op
	 * @param $valueN You can specify as many values as there are ?'s in the $field_op.
	 * @retval Object Returns itself for chainability.
	 */
	public function orWhere() {
		$argv = func_get_args();
		$argv = array('NOT YET IMPLEMENTED!');
		$this->_where(self::SQL_AND, $argv);
		return $this;
	}
	
	/**
	 * Allows the creation of an IN clause. If successfully added, the data
	 * will be put onto the same stack $_where_field_list.
	 * @author vmc <vmc@leftnode.com>
	 * @param $field The name of the field to select IN().
	 * @param $value_list The list of values actually in the IN() clause.
	 * @retval Object Returns itself for chainability.
	 */	
	public function inWhere($field, $value_list) {
		// Ensure $value_list has a value
		if ( count($value_list) > 0 && false === empty($field) ) {
			$vl_len = count($value_list);
			$is_numeric = true;
			for ( $i=0; $i<$vl_len; $i++ ) {
				if ( false === is_numeric($value_list[$i]) ) {
					$is_numeric = false;
					$value_list[$i] = $this->DB->escape($value_list[$i]);
				}
			}
			
			$where_item = NULL;
			if ( true === $is_numeric ) {
				$in_data = '(' . implode(',', $value_list) . ')';
			} else {
				$in_data = "('" . implode("', '", $value_list) . "')";
			}
			
			$where_item = $field . ' IN' . $in_data;
			$this->_where_field_list[self::SQL_AND][] = $where_item;
		}
		return $this;
	}
	
	
	/**
	 * Because several SQL classes use a WHERE clause, this class
	 * sets the list of fields in the WHERE clause. It bubbles up to
	 * the rest of the classes.
	 * @author vmc <vmc@leftnode.com>
	 * @param $where_field_list An array of fields in the WHERE clause.
	 * @retval boolean Returns true.
	 */
	public function setWhereFieldList($where_field_list) {
		if ( true === is_array($where_field_list) ) {
			$this->_where_field_list = $where_field_list;
		}
		return true;
	}
	
	/**
	 * Builds up the WHERE clause for executing in SELECT and UPDATE queries.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the built WHERE clause.
	 */
	public function buildWhereClause() {
		$where_sql = NULL;
		if ( count($this->_where_field_list) > 0 ) {
			$where_sql = " WHERE (" . implode(") " . self::SQL_AND . " (", $this->_where_field_list[self::SQL_AND]) . ")";
			
			// Ignore OR for now
			//$where_sql .= " (" . implode(") " . self::SQL_OR . " (", $this->_where_field_list[self::SQL_OR]) . ")";
		}
		return $where_sql;
	}

	/**
	 * Build the query from the functions and then execute it.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception Throws an exception if the query fails.
	 * @retval Object Returns new Artisan_Db_Result_* object where * is the database driver (Mysqli, Oracle, etc.)
	 */
	public function query() {
		$this->build();
		if ( true === $this->DB instanceof Artisan_Db ) {
			try {
				$result = $this->DB->query($this->_sql);
				return $result;
			} catch ( Artisan_Db_Exception $e ) {
				throw $e;
			}
		}
	}
	
	/**
	 * Builds the appropriate WHERE clause array. The variable $field_data is one
	 * or more elements in length. The first element is the field or list of fields
	 * with ?'s in them to compare, any other elements are the values to replace the ?'s with.
	 * @author vmc <vmc@leftnode.com>
	 * @param $type The type of WHERE to do, either AND or OR.
	 * @param $field_data The list of fields to use for the clause.
	 * @retval boolean Returns true.
	 */
	private function _where($type, $field_data) {
		$argc = count($field_data);
		
		if ( count($argc) > 0 ) {
			$fv_list = array();
			$field_op = trim($field_data[0]);
			
			if ( $argc > 1 ) {
				// Make an array of values to replace
				$fv_list = array_splice($field_data, 1, $argc, array());
			}
			
			$where_item = NULL;
		
			if ( (true === empty($fv_list) || 0 === count($fv_list)) && false === empty($field_op) ) {
				$where_item = $field_op;
			} else {
				// Create an array of locations of all ?'s in the string.
				// Each entry in here will correspond to the index of the array
				// after str_split() below allowing for easy replacement.
				$qm_loc = array();
				$qm_count = 0;
				$fo_len = strlen($field_op);
				for ( $i=0; $i<$fo_len; $i++ ) {
					if ( '?' == $field_op[$i] ) {
						$qm_loc[] = $i;
						$qm_count++;
					}
				}
		
				// Go through and perform all of the replacements
				$fvl_len = count($fv_list);
				$field_op = str_split($field_op);
				if ( $qm_count == $fvl_len ) {
					for ( $i=0; $i<$fvl_len; $i++ ) {
						$fv = $this->DB->escape($fv_list[$i]);
						if ( false === is_numeric($fv) ) {
							$fv = " '" . $fv . "'";
						}
				
						$field_op[$qm_loc[$i]] = $fv;
					}
			
					$where_item = implode('', $field_op);
				} else {
					// There are extra question marks and shouldn't be, unset everything.
					$where_item = NULL;
				}
			}
		
			// If everything passes, push this item onto the stack.
			if ( false === empty($where_item) ) {
				$this->_where_field_list[$type][] = $where_item;
			}
		}
		return true;
	}
	
	/**
	 * Converts the SQL to a string to echo out.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The built SQL.
	 */
	public function __toString() {
		return $this->_sql;
	}
}