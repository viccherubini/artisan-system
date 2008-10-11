<?php

class Artisan_Sql_Delete_Mysqli extends Artisan_Sql_Delete {

	private $CONN = NULL;
	private $RESULT = NULL;

	public function __construct($CONN) {
		if ( true === $CONN instanceof mysqli ) {
			$this->CONN = $CONN;
		}
	}

	public function __destruct() { }

	public function build() {
		$where_sql = NULL;
		if ( count($this->_where_field_list) > 0 ) {
			$where_list = array();
			foreach ( $this->_where_field_list as $field => $value ) {
				$where_list[] = $field . " = '" . $this->escape($value) . "'";
			}
			$where_sql = " WHERE " . implode(" AND ", $where_list);
		}
		
		$limit_sql = NULL;
		if ( $this->_limit > 0 ) {
			$limit_sql = " LIMIT " . $this->_limit;
		}
		
		
		$delete_start = "DELETE FROM `" . $this->_from_table . "` ";
		$this->_sql = $delete_start . $where_sql . $limit_sql;
	}

	public function query() {
		$this->build();
	}
	
	public function escape($value) {
		return $this->CONN->real_escape_string($value);
	}
}
