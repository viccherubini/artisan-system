<?php

class Artisan_Sql_Update extends Artisan_Sql {
	private $_sql = NULL;
	
	private $_table = NULL;
	
	private $_field_list = array();
	
	public function __construct() {
		
	}
	
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	public function table($table) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(
				ARTISAN_WARNING, 'Table name is empty.',
				__CLASS__, __FUNCTION__
			);
		}
		
		$this->_table = $table;
		$this->_sql = "UPDATE `" . $table . "`";
	}
	
	public function set($field_list) {
		if ( false === is_array($field_list) ) {
			$field_list = array($field_list);
		}
		
		$field_list = parent::createFieldList($table, $field_list);
		$field_list = implode(', ', $field_list);
	}
}

?>