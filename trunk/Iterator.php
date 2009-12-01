<?php

require_once 'Func.Library.php';

class Artisan_Iterator implements Iterator {
	private $_list = array();
	private $_key = 0;
	
	private $_length = 0;
	private $_filter = array();
	
	private $_limit = -1;
	private $_page = 0;
	
	public function __construct(array $list) {
		$this->_list = $list;
		$this->_key = 0;
		$this->_length = count($list);
	}

	public function rewind() {
		if ( $this->_page > 0 && $this->_limit > -1 ) {
			$this->_key = ( ($this->_page-1) * $this->_limit );
			$this->_length = ($this->_page * $this->_limit );
		} else {
			$this->_key = 0;
		}

		reset($this->_list);
	}
	
	public function current() {
		return $this->_list[$this->_key];
	}
	
	public function last() {
		return $this->_list[$this->_length-1];
	}
	
	public function key() {
		return $this->_key;
	}
	
	public function next() {
		$this->_key++;
		return next($this->_list);
	}
	
	public function valid() {
		return ( $this->_key != $this->_length && true === isset($this->_list[$this->_key]) );
	}

	public function page($page) {
		$this->_page = intval($page);
		return $this;
	}

	public function limit($limit) {
		$this->_limit = $limit;
		return $this;
	}

	public function length() {
		return $this->_length;
	}
	
	public function getAll() {
		return $this->_list;
	}
}