<?php

// insert into `table_name` ( fields ) values ( )
class Artisan_Sql_Insert extends Artisan_Sql {
	private $_sql = NULL;
	
	private $_table = NULL;
	
	public function __construct() {
		
	}
	
	
	public function __destruct() {
		unset($this->_sql);
	}
	
	public function into($table, $field_list) {
		if ( true === empty($table) ) {
			throw new Artisan_Sql_Exception(
				ARTISAN_WARNING, 'Table name is empty.',
				__CLASS__, __FUNCTION__
			);
		}
		
		$this->_table = $table;
		
		if ( false === is_array($field_list) ) {
			$field_list = array($field_list);
		}
		
		$field_list = parent::createFieldList($table, $field_list);
		$field_list = implode(', ', $field_list);
		
		$this->_sql = "INSERT INTO `" . $table . "` ( " . $field_list . " )";
		
		$this->_table = $table;
		
		return $this;
	}

	public function bind($field_data) {
		// Need to make field data safe!
		$sql = " VALUES ( '" . implode("', '", $field_data) . "' )";
		$this->_sql .= $sql;
		
		return $this;
	}
	
	public function query() {
		return parent::_query($this->_sql);
	}
	
	public function __toString() {
		return $this->_sql;
	}
	
	public function retrieve() {
		return $this->_sql;
	}
}

?>