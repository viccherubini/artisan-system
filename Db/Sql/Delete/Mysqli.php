<?php

require_once 'Artisan/Db/Sql/Delete.php';

class Artisan_Db_Sql_Delete_Mysqli extends Artisan_Db_Sql_Delete {
	public function __construct(mysqli &$CONN) {
		$this->CONN = &$CONN;
	}
	
	public function query() {
		$this->build();
		
		$result = false;
		if ( true === $this->CONN instanceof mysqli ) {
			$result = $this->CONN->query($this->_sql);
		}
		
		if ( false === $result ) {
			throw new Artisan_Db_Sql_Exception(ARTISAN_WARNING, 'Failed to execute query: "' . $sql . '"', __CLASS__, __FUNCTION__);
		}
		
		return $this;
	}
	
	public function affectedRows() {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->affected_rows;
		}
	}
	
	public function escape($value) {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->real_escape_string($value);
		}
		return addslashes($value);
	}
}