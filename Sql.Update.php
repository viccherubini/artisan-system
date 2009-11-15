<?php

require_once 'Library.php';
require_once 'Sql.php';

class Artisan_Update extends Artisan_Sql {
	protected $table = NULL;
	protected $update_field_list = array();

	public function __destruct() {
		unset($this->sql);
	}
	
	public function table($table) {
		if ( true === empty($table) ) {
			throw new Artisan_Exception('Table name is empty.');
		}
		$this->table = $table;
		return $this;
	}
	
	public function set($field_list) {
		if ( false === is_array($field_list) || count($field_list) < 1 ) {
			throw new Artisan_Exception('At least one field must be specified to be updated.');
		}
		$this->update_field_list = $field_list;
		return $this;
	}
	
	public function build() {
		$update_sql = "UPDATE `" . $this->table . "` ";
		
		$fl_len = count($this->update_field_list)-1;
		$i=0;
		$field_list_sql = " SET ";
		foreach ( $this->update_field_list as $field => $value ) {
			//if ( false === empty($value) && '`' == $value[0] ) {
			//	$field_list_sql .= $field . " = " . $this->db->escape($value);
			//} else {
				$field_list_sql .= $field . " = '" . $this->db->escape($value) . "'";
			//}
			if ( $i++ != $fl_len ) {
				$field_list_sql .= ', ';
			}
			//$i++;
		}
		
		$where_sql = $this->buildWhereClause();
		
		$this->sql = $update_sql . $field_list_sql . $where_sql;
		$this->where_field_list = array();
		return $this->sql;
	}
}