<?php

require_once 'Func.Library.php';

abstract class Artisan_Sql {
	protected $db = NULL;
	protected $sql = NULL;
	protected $where_field_list = array();
	protected $where_and_count = 0;
	protected $where_or_count = 0;
	
	const SQL_AND = 'AND';
	const SQL_OR = 'OR';
	
	public function __construct(Artisan_Db $db) {
		$this->db = clone $db;
		$this->resetWhereList();
	}
	
	public function __destruct() {
	}
	
	public function where() {
		$argv = func_get_args();
		$this->pushWhere(self::SQL_AND, $argv);
		return $this;
	}
	
	public function orWhere() {
		$argv = func_get_args();
		$this->pushWhere(self::SQL_OR, $argv);
		return $this;
	}
	
	public function inWhere($field, $value_list) {
		if ( count($value_list) > 0 && false === empty($field) ) {
			foreach ( $value_list as $i => $v ) {
				$value_list[$i] = $this->db->escape($v);
			}
			
			$where_item = NULL;
			$in_data = "('" . implode("', '", $value_list) . "')";
			
			$where_item = $field . ' IN' . $in_data;
			$this->where_field_list[self::SQL_AND][] = $where_item;
		}
		return $this;
	}
	
	public function setWhereFieldList($where_field_list) {
		if ( true === is_array($where_field_list) ) {
			$this->where_field_list = $where_field_list;
		}
		return true;
	}
	
	public function buildWhereClause() {
		$where_sql = NULL;
		$and_list = er(self::SQL_AND, $this->where_field_list, array());
		$or_list = er(self::SQL_OR, $this->where_field_list, array());
		$and_count = count($and_list);
		$or_count = count($or_list);

		if ( $and_count > 0 || $or_count > 0 ) {
			$where_sql = ' WHERE ';
			
			if ( $and_count > 0 ) {
				$where_sql .= " (" . implode(") " . self::SQL_AND . " (", $this->where_field_list[self::SQL_AND]) . ") ";
			}
			
			if ( $or_count > 0 ) {
				if ( 1 == $and_count ) {
					$where_sql .= self::SQL_OR;
				}
				$where_sql .= " (" . implode(") " . self::SQL_OR . " (", $this->where_field_list[self::SQL_OR]) . ") ";
			}
		}
		
		$this->resetWhereList();
		return $where_sql;
	}

	public function query() {
		$this->build();
		return $this->db->query($this->sql);
	}
	
	private function pushWhere($type, $field_data) {
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
						$fv = $this->db->escape($fv_list[$i]);
						$fv = " '" . $fv . "'";
				
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
				$this->where_field_list[$type][] = $where_item;
			}
		}
		return true;
	}
	
	public function __toString() {
		return $this->sql;
	}
	
	protected function resetWhereList() {
		$this->where_field_list = array(
			self::SQL_AND => array(),
			self::SQL_OR => array()
		);
	}
}