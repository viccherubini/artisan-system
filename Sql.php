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
					$fv = $this->escape($fv_list[$i]);
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

		if ( false === empty($where_item) ) {
			$this->_where_field_list[] = $where_item;
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
	
	public function buildWhereClause() {
		$where_sql = NULL;
		
		if ( count($this->_where_field_list) > 0 ) {
			$where_sql = " WHERE (" . implode(") AND (", $this->_where_field_list) . ")";
		}
		
		return $where_sql;
	}

	private function _where($type) {


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
