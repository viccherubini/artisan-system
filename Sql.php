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
	
	public function where() {		
		$argv = func_get_args();
		$argc = count($argv);
		
		$where_item = NULL;
		
		if ( 1 === $argc ) {
			$where_item = $argv[0];
		} elseif ( $argc > 1 ) {
			$field_op = trim($argv[0]);
			
			// Make an array of values to replace
			$fv_list = array_splice($argv, 1, $argc, array());
			
			$fvl_len = count($fv_list);
			for ( $i=0; $i<$fvl_len; $i++ ) {
				$fv = $this->escape($fv_list[$i]);
				if ( false === is_numeric($fv) ) {
					$fv = "'" . $fv . "'";
				}
				
				$fv_list[$i] = $fv;
			}
			
			// If there are less ?'s than elements in the array,
			// something is wrong, so ignore everything
			$qm_count = substr_count($field_op, '?');
			
			if ( $qm_count >= $fvl_len ) {
				$qm_list = array_fill(0, $qm_count, '/[?]/i');
				//$where_item = str_replace($qm_list, $fv_list, $field_op);
				
				//for ( $i=0; $i<$qm_count; $i++ ) {
				//	$field_op = str_replace('?', $fv_list[$i], $field_op);
				//}
				
				/*
				// To make this robust, we're going to loop through each character of 
				// $field_op and if we come upon a ?, replace it with the top of 
				// $fv_list queue.
				$fo_len = strlen($field_op);
				for ( $i=0; $i<$fo_len; $i++ ) {
					if ( '?' == $field_op[$i] ) {
						$replace = array_shift($fv_list);
						$field_op[$i] = $replace;
					}
				}
				*/
				$where_item = $field_op;
				
			}
			//echo $where_item;
		}		
		/*
		switch ( $argc ) {
			case 1: {
				// The first element is just a plain "field op value" query, no replacement
				
				break;
			}
			
			case 2: {
				// The first element is "field op ?" and the second value is the replacement
				$field_op = trim($argv[0]);
				$field_value = $argv[1];
				
				// If field_value is numeric, don't include the quotes, otherwise, use them
				$field_value = $this->escape($field_value);
				if ( false === is_numeric($field_value) ) {
					$field_value = "'" . $field_value . "'";
				}
				
				$where_item = str_replace('?', $field_value, $field_op);
				
				break;
			}
			
			default: {
				$where_item = NULL;
				break;
			}
		} 
		
		if ( false === empty($where_item) ) {
			$this->_where_field_list[] = $where_item;
		}
		*/
		
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
	
	public function buildWhereClause() {
		$where_sql = NULL;
		
		if ( count($this->_where_field_list) > 0 ) {
			$where_sql = " WHERE (" . implode(") AND (", $this->_where_field_list) . ")";
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
