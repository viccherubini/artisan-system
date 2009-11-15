<?php

require_once 'Library.php';

class Artisan_Vo {

	public function __construct($array = array()) {
		if ( true === is_array($array) && count($array) > 0 ) {
			$this->init($array);
		}
	}
	
	public function __destruct() { }

	public function __get($e) {
		if ( false === isset($this->$e) ) {
			return NULL;
		}
	}
	
	public function __toString() {
		return pre_print_r($this, true);
	}
	
	public function __unset($name) {
		if ( true === $this->key($name) ) {
			unset($this->$name);
		}
	}

	public function exists() {
		$argv = func_get_args();
		$argc = func_num_args();
		if ( 1 == $argc && true === is_array($argv[0]) ) {
			$argv = current($argv);
		}
		
		$found = true;
		foreach ( $argv as $e ) {
			if ( false === property_exists($this, $e) ) {
				$found = false;
			}
		}
		return $found;
	}
	
	public function length() {
		return count(get_object_vars($this));
	}
	
	public function key($k) {
		return property_exists($this, $k);
	}
	
	public function toArray() {
		$vo_a = $this->unwind($this);
		return $vo_a;
	}

	protected function init($root) {
		foreach ( $root as $k => $v ) {
			if ( true === is_array($v) ) {
				$this->$k = new Artisan_Vo($v);
			} else {
				$this->$k = $v;
			}
		}
	}
	
	/**
	 * Unwinds the Value Object back to an array.
	 * @author vmc <vmc@leftnode.com>
	 * @param $root The root of the object to start at.
	 * @retval array The unwound Value Object array.
	 */
	private function unwind($root) {
		$x = array();
		if ( true === is_object($root) ) {
			foreach ( $root as $key => $value ) {
				$x[$key] = $this->unwind($value);
			}
		} else {
			$x = strval($root);
		}
		return $x;
	}
}
