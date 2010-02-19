<?php

require_once 'Func.Library.php';

class Artisan_Iterator implements Iterator {
	private $list = array();
	private $key = 0;
	
	private $length = 0;
	private $filter = array();
	
	private $limit = -1;
	private $page = 0;
	
	public function __construct(array $list) {
		$this->list = $list;
		$this->key = 0;
		$this->length = count($list);
	}

	public function rewind() {
		if ( $this->page > 0 && $this->limit > -1 ) {
			$this->key = ( ($this->page-1) * $this->limit );
			$this->length = ($this->page * $this->limit );
		} else {
			$this->key = 0;
		}

		reset($this->list);
	}
	
	public function current() {
		return $this->list[$this->key];
	}
	
	public function last() {
		return $this->list[$this->length-1];
	}
	
	public function key() {
		return $this->key;
	}
	
	public function next() {
		$this->key++;
		return next($this->list);
	}
	
	public function valid() {
		return ( $this->key != $this->length && true === isset($this->list[$this->key]) );
	}

	public function page($page) {
		$this->page = intval($page);
		return $this;
	}

	public function limit($limit) {
		$this->limit = $limit;
		return $this;
	}

	public function length() {
		return $this->length;
	}
	
	public function getAll() {
		return $this->list;
	}
}
