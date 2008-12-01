<?php

require_once 'Artisan/Functions/Array.php';

require_once 'Artisan/Db/Result.php';

class Artisan_Db_Result_Mysqli extends Artisan_Db_Result {
	private $RESULT = NULL;

	public function __construct(mysqli_result &$RES) {
		$this->RESULT = &$RES;
	}

	public function fetch($field = NULL) {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$data = $this->RESULT->fetch_assoc();
			if ( true === is_null($data) ) {
				$this->free();
			} else {
				reset($data);
				
				// Check if only one field was returned, if so, return that
				if ( 1 === count($data) ) {
					$data = current($data);
				} else {
					if ( false === empty($field) && true === asfw_exists($field, $data) ) {
						$data = $data[$field];				
					}
				}			
			}

			return $data;
		}
		
		return array();
	}

	public function fetchAll($key_on_primary = false) {

	}

	public function free() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			$this->RESULT->free();
		}
		return true;
	}

	public function numRows() {
		if ( true === $this->RESULT instanceof mysqli_result ) {
			return $this->RESULT->num_rows;
		}
		return 0;
	}
}
