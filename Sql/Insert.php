<?php

// comme here
abstract class Artisan_Sql_Insert extends Artisan_Sql {
	///< The actual SQL query in string form.
	protected $_sql = NULL;
	
	///< The main table the query is inserting INTO.
	protected $_into_table = NULL;
	
	///< The fields to insert data into, must be an associative array.
	protected $_insert_field_list = array();
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	public function here() { }



	public function into($table, $insert_fields) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$table = trim($table);

		$this->_into_table = $table;
		
		$this->_insert_field_list = asfw_sanitize_field_list($insert_fields);

		return $this;
	}

	public function values($insert_values) {

	}

	public function __toString() {
		return $this->_sql;
	}
	
	abstract public function build();
	abstract public function query();
	abstract public function affectedRowCount();
	abstract public function escape($value);
}

?>
