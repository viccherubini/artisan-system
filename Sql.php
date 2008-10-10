<?php

Artisan_Library::load('Sql/Monitor');
Artisan_Library::load('Sql/Exception');

class Artisan_Sql {
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
	
	public function setWhereFieldList($where_field_list) {
		if ( true === is_array($where_field_list) ) {
			$this->_where_field_list = $where_field_list;
		}
		return true;
	}
	
	
}

?>
