<?php

Artisan_Library::load('Sql/Exception');

class Artisan_Sql {
	protected $_sql = NULL;
	
	///< The list of fields to use in the WHERE clause.
	protected $_where_field_list = array();
	
	const SQL_AND = 'AND';
	const SQL_OR = 'OR';
	

	public function __construct() {
		
	}
	

	public function __destruct() {

	}
	
	public function where($where_fields) {
		if ( true === asfw_is_assoc($where_fields) ) {
			$this->setWhereFieldList(asfw_sanitize_field_list($where_fields));
		} else {
			$this->setWhereFieldList(array());
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
	
	public function buildWhereClause($where_field_list) {
		$where_sql = NULL;
		if ( count($where_field_list) > 0 ) {
			$logical_op_list = array('=', '<', '>', '<=', '>=', '<>', '!=', 'LIKE');
			$where_list = array();
			foreach ( $where_field_list as $field => $value ) {
				// See if field has an operator at the end of it, if so, use that
				// rather than the equals operator, otherwise, use equals by default.
				$field = trim($field);
				
				if ( false !== strpos($field, ' ') ) {
					// There's a space, see if an operator exists
					$op_list = explode(' ', $field);
					if ( count($op_list) != 2 ) {
						$field = $op_list[0];
						$operator = $op_list[count($op_list)-1];
					} else {
						$field = $op_list[0];
						$operator = $op_list[1];
					}
						
					if ( false === in_array($operator, $logical_op_list) ) {
						$operator = '=';
					}
				} else {
					$operator = '=';
				}
				
				$where_list[] = $field . ' ' . $operator . " '" . $this->escape($value) . "'";
			}
			$where_sql = " WHERE " . implode(" AND ", $where_list);
		}
		
		return $where_sql;
	}
	
	
	/**
	 * Converts the SQL to a string to echo out.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The built SQL.
	 */
	public function __toString() {
		$this->build();
		return $this->_sql;
	}
}
