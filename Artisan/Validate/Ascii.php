<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * Static class that contains methods to validate ASCII values.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Ascii extends Artisan_Validate {
	private $_val = NULL;

	public function __construct($val = NULL) {
		$this->_val = trim($val);
	}
	
	/**
	 * Test if a value is composed entirely of ASCII values.
	 * @author vmc <vmc@leftnode.com>
	 * @author rafshar <rafshar@gmail.com>
	 * @param $val The value to test.
	 * @retval boolean True if $val is all ASCII text, false otherwise.
	 */
	public function isValid($val = NULL) {
		if ( true === empty($val) ) {
			$val = $this->_val;
		}
		
		if ( true === empty($val) ) {
			return false;
		}

		$clamp_low = ord(' ');
		$clamp_high = ord('~');

		$len = strlen($val);
		$is_ascii = true;
		for ( $i=0; $i<$len; $i++ ) {
			if ( ord($val[$i]) < $clamp_low || ord($val[$i]) > $clamp_high ) {
				$is_ascii = false;
			}
		}
		return $is_ascii;
	}
}
