<?php

require_once 'Artisan/Functions/Array.php';

require_once 'Artisan/Db/Result.php';

require_once 'Artisan/VO.php';

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
			}
			return $data;
		}
		return NULL;
	}
	
	public function fetchVo() {
		$vo = $this->fetch();
		if ( false === empty($vo) ) {
			$vo = new Artisan_VO($vo);
			return $vo;
		}
		return NULL;
	}

	public function fetchAll($key_on_primary = false) {
		$result_data = array();
		while ( $row = $this->fetch() ) {
			$result_data[] = $row;
		}
		return $result_data;
	}

	public function fetchAllVo() {
		$result_data = array();
		while ( $row = $this->fetch() ) {
			$result_data[] = new Artisan_VO($row);
		}
		return $result_data;
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
