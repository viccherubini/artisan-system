<?php

abstract class Artisan_Sql_Delete extends Artisan_Sql {
	///< The actual SQL query in string form.
	protected $_sql = NULL;
	
	///< The main table the query is selecting FROM.
	protected $_from_table = NULL;
	
	///< The alias of the table the query is selecting FROM.
	protected $_from_table_alias = NULL;
	
	///< The list of fields to use in the WHERE clause.
	//protected $_where_field_list = array();
		
	
	public function __construct() {
		$this->_sql = NULL;
	}
	
	public function __destruct() {
		unset($this->_sql, $this->_from_table, $this->_from_table_list, $this->_where_field_list);
	}
	
	
	public function from($table) {
	
	}
	
	/*
	public function where($where_fields) {
		if ( true === asfw_is_assoc($where_fields) ) {
			$this->setWhereFieldList(asfw_sanitize_field_list($where_fields));
		}
		
		return $this;
	}
	*/
	
	
	
	
	abstract public function build();
	abstract public function query();
}

?>
