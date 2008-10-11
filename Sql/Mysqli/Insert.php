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
		// Ensure that the connection actually exists before building the query.
		if ( false === $this->CONN instanceof mysqli ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The current connection to the database does not exist, no query can be built.', __CLASS__, __METHOD__);
		}
		
		$insert_sql  = "INSERT INTO `" . $this->_into_table . "`";
		
		$insert_field_sql = NULL;
		if ( count($this->_insert_field_list) > 0 ) {
			$insert_field_sql = " (" . implode(', ', $this->_insert_field_list) . ") ";
		}
		
		$value_list = array();
		$insert_value_sql = " VALUES (";
		foreach ( $this->_insert_field_value_list as $value ) {
			$value = $this->escape($value);
			switch ( strtoupper($value) ) {
				case NULL: {
					$value_list[] = 'NULL';
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

		$this->_sql = $insert_sql . $insert_field_sql . $insert_value_sql;
	}
	
	
	public function query() {
		// Assume $this->_sql has not been built, so build it. If it
		// has been built, it'll simply be overwritten.
		$this->build();
		
		if ( true === empty($this->_sql) ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, 'The INSERT query is empty, it can not be executed.', __CLASS__, __FUNCTION__);
		}
		
		$result = $this->CONN->query($this->_sql);
		
		if ( false === $result ) {
			throw new Artisan_Sql_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
		}
		
		return $this;
	}
	
	public function affectedRows() {
		return $this->CONN->affected_rows;
	}
		
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
}

?>
