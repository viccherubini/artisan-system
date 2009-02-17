<?php


class Artisan_Db_Result_Aggregate {
	private $_data = array();
	
	public function __construct(&$data) {
		$this->_data = &$data;
	}
	
	public function Avg($field) {
		$total = $count = 0;
		foreach ( $this->_data as $datum ) {
			$count++;
			$total += @$datum[$field];
		}
		
		$avg = 0;
		if ( $count > 0 ) {
			$avg = $total / $count;
		}
		return $avg;
	}
	
	public function Sum($field) {
		$sum = 0;
		foreach ( $this->_data as $datum ) {
			$sum += @$datum[$field];
		}
		return $sum;
	}
	
	public function Count($field) {
		return count($this->_data);
	}
}