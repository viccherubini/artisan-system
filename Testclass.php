<?php

class Testclass extends Artisan_Unittest {
	public function __construct() {

	}


	function test() {
		$this->assertFalse(4 == 5);
		$this->assertTrue(4 == 4);

		$this->assertTrue( $this->selftest(5) );
		$this->assertTrue( $this->selftest(-9) );
		$this->assertFalse( $this->selftest(5) );
	}

	function selftest($i) {
		if ( $i > 0 ) {
			return true;
		} else {
			return false;
		}
	}

}
