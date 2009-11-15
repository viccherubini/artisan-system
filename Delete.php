<?php

require_once 'Library.php';
require_once 'Sql.php';

class Artisan_Delete extends Artisan_Sql {
	protected $from_table = NULL;
	protected $limit = 0;
	
	public function __destruct() {
		unset($this->sql, $this->from_table, $this->limit);
	}
	
	public function from($table) {
		$this->from_table = trim($table);
		return $this;
	}
	
	public function limit($limit) {
		$this->limit = abs(intval($limit));
		return $this;
	}

	public function build() {
		$where_sql = $this->buildWhereClause();
		
		$limit_sql = NULL;
		if ( $this->limit > 0 ) {
			$limit_sql = " LIMIT " . $this->limit;
		}
		
		$delete_start = "DELETE FROM `" . $this->from_table . "` ";
		$this->sql = $delete_start . $where_sql . $limit_sql;
		
		return $this->sql;
	}
}