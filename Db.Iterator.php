<?php

require_once 'Func.Library.php';

class Artisan_Db_Iterator implements Iterator {
	private $result = NULL;
	private $object = NULL;
	private $key = 0;
	
	private $filter = array();
	private $filter_count = 0;
	
	private $limit = -1;
	private $page = 0;
	
	public function __construct(Artisan_Result $result, $object) {
		$this->reset();
		
		$this->result = $result;
		$this->object = $object;
	}

	public function __destruct() {
		$this->object = NULL;
		if ( NULL !== $this->result ) {
			$this->result->free();
			$this->result = NULL;
		}
	}

	public function current() {
		return $this->load($this->key);
	}
	
	public function last() {
		$num_rows = $this->result->numRows()-1;
		return $this->load($num_rows);
	}

	public function key() {
		return $this->key;
	}

	public function next() {
		$this->key++;
		return $this->key;
	}

	public function rewind() {
		$this->key = 0;
		$this->result->row(0);
		return true;
	}

	public function valid() {
		return ( $this->key != $this->result->numRows() );
	}

	public function __get($name) {
		return $this->object;
	}
	
	public function fetch() {
		$result_list = array();
		
		$i=0;
		foreach ( $this as $obj ) {
			if ( true === $this->applyFilter($obj->get()) && ( -1 == $this->limit || $i < $this->limit) ) {
				$result_list[] = clone $obj;
				$i++;
			}
		}
		
		$this->reset();

		return new Artisan_Iterator($result_list);
	}
	
	public function length() {
		return $this->result->numRows();
	}
	
	public function limit($limit) {
		$limit = intval($limit);
		if ( $limit > -1 ) {
			$this->limit = $limit;
		}
		return $this;
	}
	
	public function page($page) {
		$this->page = intval($page);
		return $this;
	}
	
	public function filter($field, $value) {
		$this->limit = -1;
		$this->filter[] = array($field, $value);
		$this->filter_count++;
		return $this;
	}
	
	private function load($i) {
		$this->result->row($i);
		$row = $this->result->fetch();

		$model = clone $this->object;
		if ( true === is_array($row) ) {
			$model->loadModel($row);
		}

		return $model;
	}
	
	private function hasFilter() {
		if ( true === is_array($this->filter) && $this->filter_count > 0 ) {
			return true;
		}
		return false;
	}
	
	private function applyFilter($data_array) {
		if ( false === $this->hasFilter() ) {
			return true;
		}
		
		$passed = false;
		$match_count = 0;

		foreach ( $this->filter as $filter ) {
			$field = $filter[0];
			$value = $filter[1];

			/* Get the operator cheaply. */
			$op_bits = explode(' ', $field);
			
			$field = trim(er(0, $op_bits));
			$op = trim(er(1, $op_bits));
			
			if ( true == isset($data_array[$field]) ) {
				switch ( $op ) {
					case '==':
					case '=': {
						if ( $data_array[$field] == $value ) {
							$match_count++;
						}						
						break;
					}
					
					case '!=':
					case '<>': {
						if ( $data_array[$field] != $value ) {
							$match_count++;
						}
						break;
					}
					
					case '>=': {
						if ( $data_array[$field] >= $value ) {
							$match_count++;
						}
						break;
					}
					
					case '<=': {
						if ( $data_array[$field] <= $value ) {
							$match_count++;
						}
						break;
					}
					
					case '<': {
						if ( $data_array[$field] < $value ) {
							$match_count++;
						}
						break;
					}
					
					case '>': {
						if ( $data_array[$field] > $value ) {
							$match_count++;
						}
					}
				}
				
				if ( $match_count == $this->filter_count ) {
					$passed = true;
				}
			}
		}
		
		return $passed;
	}
	
	private function reset() {
		$this->page = 0;
		$this->limit = -1;
		$this->filter = array();
		$this->filter_count = 0;
		return true;
	}
}