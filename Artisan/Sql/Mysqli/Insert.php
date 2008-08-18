<?php

class Artisan_Sql_Insert_Mysqli extends Artisan_Sql_Insert {
	private $CONN = NULL;
	
	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}
	
	public function __destruct() {
	
	}
	
	public function build() {
		$field_list = array_keys($this->_insert_field_list);
		$field_list = artisan_sanitize_fields($field_list);
		
		$insert_sql  = "INSERT INTO `" . $this->_into_table . "`";
		$insert_sql .= " (" . implode(', ', $field_list) . ")";
		
		$values_sql = NULL;
		$values_list = array();
		foreach ( $this->_insert_field_list as $value ) {
			$values_list[] = $this->escape($value);
		}
		$values_sql = implode("', '", $values_list);
		
		$insert_sql .= " VALUES('" . $values_sql . "')";

		$this->_sql = $insert_sql;		
	}
	
	
	public function query() {
		// Assume $this->_sql has not been built, so build it. If it
		// has been built, it'll simply be overwritten.
		$this->build();
		
		if ( false === empty($this->_sql) ) {
			$result = $this->CONN->query($this->_sql);

			if ( false === $result ) {
				throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
			}

			return $this;
		} else {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'Query is empty', __CLASS__, __FUNCTION__);
		}
	}
	
	public function affectedRowCount() {
		return $this->CONN->affected_rows;
	}
		
	public function escape($value) {
		$value = trim($value);
		return $this->CONN->real_escape_string($value);
	}
}

?>
