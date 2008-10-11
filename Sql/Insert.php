<?php

abstract class Artisan_Sql_Insert extends Artisan_Sql {
	///< The actual SQL query in string form.
	protected $_sql = NULL;
	
	///< The main table the query is inserting INTO.
	protected $_into_table = NULL;
	
	///< The fields to insert data into, must be an associative array.
	protected $_insert_field_list = array();
	
	protected $_insert_field_value_list = array();
	
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	public function into($table, $insert_fields = array()) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL INSERT class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$table = trim($table);

		$this->_into_table = $table;
		$this->_insert_field_list = asfw_sanitize_field_list($insert_fields);

		return $this;
	}

	public function values() {
		$argc = func_num_args();
		if ( 0 === $argc ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The no values were passed into the method to insert.', __CLASS__, __FUNCTION__);
		}

		$ifl_len = count($this->_insert_field_list);
		if ( $argc != $ifl_len && $ifl_len > 0 ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 
				'The number of values to insert does not match the column count: ' . $argc . ' value(s) and ' . $ifl_len . ' column(s).',
				__CLASS__, __FUNCTION__
			);
		}

		$this->_insert_field_value_list = func_get_args();
		
		return $this;
	}

	public function __toString() {
		return $this->_sql;
	}
	
	abstract public function build();
	abstract public function query();
	abstract public function affectedRows();
	abstract public function escape($value);
}

?>
