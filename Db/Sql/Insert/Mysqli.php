<?php

require_once 'Artisan/Db/Sql/Insert.php';

class Artisan_Db_Sql_Insert_Mysqli extends Artisan_Db_Sql_Insert {
	public function __construct(mysqli &$CONN) {
		$this->CONN = &$CONN;
	}
	
	public function query() {
	
	}
	
	public function affectedRows() {
	
	}
	
	public function escape($value) {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->real_escape_string($value);
		}
		return addslashes($value);
	}
}
