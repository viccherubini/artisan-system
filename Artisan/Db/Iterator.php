<?php

class Artisan_Db_Iterator implements Iterator {
	private $_result = NULL;
	private $_object = NULL;
	private $_key = 0;
	
	private $_filter = array();
	private $_filter_count = 0;
	
	private $_limit = -1;
	private $_page = 0;
	
	public function __construct(Artisan_Db_Result $result, $object) {
		$this->_reset();
		
		$this->_result = $result;
		$this->_object = $object;
	}

	public function __destruct() {
		$this->_object = NULL;
		if ( NULL !== $this->_result ) {
			$this->_result->free();
			$this->_result = NULL;
		}
	}

	/**
	 * Returns the current matched element.
	 * @author tandreas <tandreas@gmail.com>
	 * @retval object Returns the current matched element of the iteration list.
	 */
	public function current() {
		return $this->_load($this->_key);
	}
	
	
	
	public function last() {
		$num_rows = $this->_result->numRows()-1;
		return $this->_load($num_rows);
	}
	
	
	/**
	 * Returns the key of the current element.
	 * @author tandreas <tandreas@gmail.com>
	 * @retval int Returns the integer key of the current element.
	 */
	public function key() {
		return $this->_key;
	}
	
	
	/**
	 * Moves to the next element.
	 * @author tandreas <tandreas@gmail.com>
	 * @retval int Returns the next key's value.
	 */
	public function next() {
		$this->_key++;
		return $this->_key;
	}


	/**
	 * Rewinds to the first row of the result.
	 * @author tandreas <tandreas@gmail.com>
	 * @retval boolean Returns true.
	 */
	public function rewind() {
		$this->_key = 0;
		$this->_result->row(0);
		return true;
	}
	
	
	/**
	 * Determines if the next() or current() calls are valid.
	 * @author tandreas <tandreas@gmail.com>
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if they are valid, false otherwise.
	 */
	public function valid() {
		return ( $this->_key != $this->_result->numRows() );
	}
	
	/**
	 * Magic method to get the current object.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the field of the object to get. Note: This is currently ignored.
	 * @retval object Returns the current object in the iteration.
	 */
	public function __get($name) {
		return $this->_object;
	}
	
	public function fetch() {
		$result_list = array();
		
		$i=0;
		foreach ( $this as $obj ) {
			if ( true === $this->_applyFilter($obj->get()) && ( -1 == $this->_limit || $i < $this->_limit) ) {
				$result_list[] = clone $obj;
				$i++;
			}
		}
		
		$this->_reset();
		
		//if ( $this->_page > 0 && $this->_limit > -1 ) {
		//	$offset = ( ($this->_page-1) * $this->_limit );
		//	$length = ($this->_page * $this->_limit );
		//	$result_list = array_slice($result_list, $offset, $length);
		//}
		
		//if ( false === empty($field) && 1 == $length ) {
		//	$item = current($result_list);
		//	return $item->$field;
		//}
		
		return new Artisan_Iterator($result_list);
	}
	
	public function length() {
		return $this->_result->numRows();
	}
	
	public function limit($limit) {
		$limit = intval($limit);
		if ( $limit > -1 ) {
			$this->_limit = $limit;
		}
		return $this;
	}
	
	public function page($page) {
		$this->_page = intval($page);
		return $this;
	}
	
	
	public function filter($field, $value) {
		$this->_limit = -1;
		$this->_filter[] = array($field, $value);
		$this->_filter_count++;
		return $this;
	}
	
	/**
	 * Loads up the specified object during iteration.
	 * @author vmc <vmc@leftnode.com>
	 * @param $i The key/index to load from.
	 * @retval Object Returns the built object.
	 */
	private function _load($i) {
		$this->_result->row($i);
		$row = $this->_result->fetch();

		$model = clone $this->_object;
		if ( true === is_array($row) ) {
			$model->loadModel($row);
		}

		return $model;
	}
	
	
	private function _hasFilter() {
		if ( true === is_array($this->_filter) && $this->_filter_count > 0 ) {
			return true;
		}
		return false;
	}
	
	private function _applyFilter($data_array) {
		if ( false === $this->_hasFilter() ) {
			return true;
		}
		
		$passed = false;
		$match_count = 0;

		foreach ( $this->_filter as $filter ) {
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
				
				if ( $match_count == $this->_filter_count ) {
					$passed = true;
				}
			}
		}
		
		return $passed;
	}
	
	private function _reset() {
		$this->_page = 0;
		$this->_limit = -1;
		$this->_filter = array();
		$this->_filter_count = 0;
		return true;
	}
}