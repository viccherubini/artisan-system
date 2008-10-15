<?php

class Artisan_VO {

	public function __construct($array) {
		if ( true === is_array($array) && count($array) > 0 ) {
			$this->_init($array);
		}
	}
	
	public function __destruct() {
		
	}

	protected function _init($root) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$this->$k = new Artisan_VO($v);
			} else {
				$this->$k = $v;
			}
		}
	}
	
	public function __get($e) {
		if ( false === isset($this->$e) ) {
			return NULL;
		}
	}
	
	public function __toString() {
		return asfw_print_r($this, true);
	}
}
