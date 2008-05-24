<?php

class Artisan_Unittest {
	public $_test_count = 0;
	public $_test_passed = 0;
	public $_test_failed = 0;

	public function __construct() {
		$this->_test_count = 0;
		$this->_test_passed = 0;
		$this->_test_failed = 0;
	}


	public function __destruct() {

	}


	public function assertTrue($expr) {
		$this->_test_count++;

		if ( true === $expr ) {
			$this->_test_passed++;
		} else {
			$this->_test_failed++;
		}
	}


	public function assertFalse($expr) {
		$this->_test_count++;

		if ( false === $expr ) {
			$this->_test_passed++;
		} else {
			$this->_test_failed++;
		}

	}
}

?>
