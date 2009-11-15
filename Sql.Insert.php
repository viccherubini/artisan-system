<?php

require_once 'Sql.php';

class Artisan_Sql_Insert extends Artisan_Sql {
	protected $into_table = NULL;
	protected $insert_field_list = array();
	protected $insert_field_value_list = array();
	protected $replace = false;
	
	public function __destruct() {
		unset($this->sql, $this->into_table, $this->insert_field_list, $this->insert_field_value_list);
	}
	
	public function into($table) {
		$table = trim($table);
		$this->into_table = $table;
		
		// Determine if the insert fields are listed or just an array
		$insert_fields = array();
		$argc = func_num_args();
		if ( $argc > 1 ) {
			$argv = func_get_args();
			array_shift($argv); // Remove the table name
			
			if ( true === is_array($argv[0]) && 2 === $argc ) {
				$insert_fields = $argv[0];
			} else {
				$insert_fields = $argv;
			}
		}
		$this->insert_field_list = sanitize_field_list($insert_fields);
		return $this;
	}

	public function values() {
		$argc = func_num_args();
		if ( 0 === $argc ) {
			throw new Artisan_Exception('No values were passed into the method to insert.');
		}

		// See if only one argument was set and it's an array, if so
		// use that as the data rather than func_get_args()
		if ( 1 === $argc && true === is_array(func_get_arg(0)) ) {
			$arg = func_get_arg(0);
			
			// If this is an associative array, use the keys as the fields and values
			// as the values to insert.
			if ( true === is_assoc($arg) ) {
				$this->insert_field_list = sanitize_field_list(array_keys($arg));
			}
			$this->insert_field_value_list = array_values($arg);
		} else {
			$ifl_len = count($this->insert_field_list);
			if ( $argc != $ifl_len && $ifl_len > 0 ) {
				$exception = "The number of values to insert does not match the column count: {$argc} value(s) and {$fl_len} column(s).";
				throw new Artisan_Exception($exception);
			}
			$this->insert_field_value_list = func_get_args();
		}
		return $this;
	}
	
	public function setReplace($replace) {
		$this->replace = $replace;
		return $this;
	}
	
	public function build() {
		$query_type = 'INSERT';
		if ( true === $this->replace ) {
			$query_type = 'REPLACE';
		}
	
		$insert_sql = $query_type . " INTO `" . $this->into_table . "`";
		
		$insert_field_sql = NULL;
		if ( count($this->insert_field_list) > 0 ) {
			$insert_field_sql = " (" . implode(', ', $this->insert_field_list) . ") ";
		}
		
		$value_list = array();
		$insert_value_sql = " VALUES (";
		foreach ( $this->insert_field_value_list as $value ) {
			$value = $this->db->escape($value);
			switch ( strtoupper($value) ) {
				case NULL: {
					$value_list[] = "''";
					break;
				}
				
				case 'NULL': 
				case 'NOW()': {
					$value_list[] = $value;
					break;
				}
			
				default: {
					$value_list[] = "'" . $value . "'";
					break;
				}
			}
		}
		$insert_value_sql = " VALUES (" . implode(", ", $value_list) . ") ";

		$this->sql = $insert_sql . $insert_field_sql . $insert_value_sql;
		return $this->sql;
	}
}