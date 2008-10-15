<?php

class Artisan_Sql_Update extends Artisan_Sql {
	protected $_table = NULL;
	
	protected $_field_list = array();
	
	public function __construct() {
		
	}
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	public function table($table) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Failed to create valid SQL UPDATE class, the table name is empty.', __CLASS__, __FUNCTION__);
		}
		
		$this->_table = $table;
	}
	
	public function set($field_list) {
	}
}
