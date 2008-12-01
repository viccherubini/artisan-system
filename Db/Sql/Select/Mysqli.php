<?php

require_once 'Artisan/Db/Result/Mysqli.php';

require_once 'Artisan/Db/Sql/Select.php';

require_once 'Artisan/Db/Exception.php';

class Artisan_Db_Sql_Select_Mysqli extends Artisan_Db_Sql_Select {
	
	public function __construct(mysqli &$CONN) {
		$this->CONN = &$CONN;
	}
	
	public function query() {
		$this->build();
		
		if ( true === $this->CONN instanceof mysqli ) {
			$result = $this->CONN->query($this->_sql);
			
			if ( true === $result instanceof mysqli_result ) {
				return new Artisan_Db_Result_Mysqli($result);
			}
		}
		
		if ( false === $result ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'Failed to execute query: "' . $sql . '"', __CLASS__, __FUNCTION__);
		}
		
		return $result;
	}
	
	public function escape($value) {
		if ( true === $this->CONN instanceof mysqli ) {
			return $this->CONN->real_escape_string($value);
		}
		return addslashes($value);
	}
}