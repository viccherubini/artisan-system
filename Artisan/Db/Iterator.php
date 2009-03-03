<?php

class Artisan_Db_Iterator implements Iterator {

	private $_result;
	private $_object;
	private $_key;
	
	public function __construct(Artisan_Db_Result $result, $object) {
		if ( false === is_object($object) ) {
			if ( false === class_exists($object) ) {
				throw new Artisan_Exception(ARTISAN_ERROR, "The class specified '" . $object . "' does not exist.");
			}
		}
		
		$this->_result = $result;
		$this->_object = $object;
		$this->_key = 0;
	}
	
	// return the current element
	public function current() {
		if ( false === $this->valid() ) {
			return NULL;
		}
		return $this->_load($this->_key);
	}
	
	// return the key of the current element
	public function key() {
		return $this->_key;
	}
	
	// move forward to the next element
	public function next() {
		if ( false === $this->valid() ) {
			return NULL;
		}	
		$this->_key++;
	}

	// rewind to the firest element
	public function rewind() {
		$this->_key = 0;
	}
	
	// Check if there is a current element after calls to rewind() or next(). 
	public function valid() {
		return ( $this->_key != $this->_result->numRows() );
	}
	
	public function __get($name) {
		return $this->_object;
	}
	
	/**
	 * Returns a specific object index in the iterator, treating it as an array.
	 * This is here because PHP can't overload the [] operator.
	 * @author vmc <vmc@leftnode.com>
	 * @param $i The integer index to return.
	 * @retval Object Returns a new object of whatever type of object was passed into the Iterator.
	 */
	public function index($i) {
		$i = intval($i);
		if ( $i < 0 ) {
			return NULL;
		}

		if ( $i >= $this->_result->numRows() ) {
			return NULL;
		}

		return $this->_load($i);
	}
	
	/**
	 * Returns the object passed into the Iterator so new objects can be built from it.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the object in the Iterator.
	 */
	public function obj() {
		return $this->_object;
	}
	
	/**
	 * Loads up the specified object during iteration.
	 * @author vmc <vmc@leftnode.com>
	 * @param $i The key/index to load from.
	 * @retval Object Returns the built object.
	 */
	private function _load($i) {
		$this->_result->row($i);
		$data = $this->_result->fetch();
		
		// If the object is a string, try to build it first.
		if ( false === is_object($this->_object) ) {
			$this->_object = new $this->{_object}();
		}
		
		// All objects passed in here should implement Artisan_Interface_Iterable.
		// There should be a primary key in $data that each loadFromArray() in the
		// object checks for.
		$this->_object->loadFromArray($data);
		return $this->_object;
	}
}