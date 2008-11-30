<?php

require_once 'Artisan/Db/Result/Interface.php';

class Artisan_Db_Result_Mysqli implements Artisan_Db_Result_Interface {
	private $RESULT = NULL;
	
	public function __construct(mysqli_result &$RES) {
		$this->RESULT = $RES;
	}
	
	public function __destruct() {
	
	}
	
	public function fetch($field = NULL) {
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
	
	public function fetchAll() {
		
	}
	
	public function free() {
		$this->RESULT->free();
		return true;
	}
}