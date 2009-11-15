<?php

require_once 'Library.php';

class Artisan_Db_Result {
	private $result = NULL;

	public function __construct(mysqli_result $result) {
		$this->result = $result;
	}
	
	public function __destruct() {
		unset($this->result);
	}
	
	public function row($offset) {
		$offset = abs($offset);
		$row_count = $this->numRows();
		if ( $offset < $row_count ) {
			$this->result->data_seek($offset);
		}
		return true;
	}

	public function fetch($field = NULL) {
		$data = $this->result->fetch_assoc();
		reset($data);
		if ( false === empty($field) && true === isset($data[$field]) ) {
			$data = $data[$field];
		}
		
		return $data;
	}

	public function fetchAll() {
		$result_data = array();
		while ( $row = $this->fetch() ) {
			$result_data[] = $row;
		}
		return $result_data;
	}

	public function free() {
		$this->result->free();
		return true;
	}

	public function numRows() {
		return $this->result->num_rows;
	}
}