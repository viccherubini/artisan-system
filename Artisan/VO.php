<?php

class VO {

	public function __construct($array) {
		if ( true === is_array($array) && count($array) > 0 ) {
			$this->_init($array, $this);
		}
	}
	
	public function __destruct() {
		
	}

	private function _init($root, &$t) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$t->$k = $t;
				$this->_init($v, $t);
			} else {
				$t->$k = $v;
			}
		}
	}
	
	public function __get($e) {
		if ( false === isset($this->$e) ) {
			return NULL;
		}
	}
}

?>