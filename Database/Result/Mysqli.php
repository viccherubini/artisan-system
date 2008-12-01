<?php

class Artisan_Db_Result_Mysqli extends Artisan_Db_Result {
	private $RESULT = NULL;

	public function __construct(mysqli_result &$RES) {
		$this->RESULT = $RES;
	}

	public function fetch($field = NULL) {

	}

	public function fetchAll($key_on_primary = false) {

	}

	public function free() {
		if ( true === is_object($this->RESULT) ) {
			$this->RESULT->free();
		}
		return true;
	}

	public function numRows() {
		if ( true === is_object($this->RESULT) ) {
			return $this->RESULT->num_rows;
		}
	}
}
