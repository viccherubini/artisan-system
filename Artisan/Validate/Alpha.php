<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * Static class that contains methods to validate alphabetic strings.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Alpha extends Artisan_Validate {
	private $_s = NULL;

	public function __construct($s = NULL) {
		$this->_s = trim($s);
	}
	
	/**
	 * Validates whether a submitted string is comprised of letters only.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $s The string to test.
	 * @retval boolean True if the value is all alphabetic, false otherwise.
	 */
	public function isValid($s = NULL) {
		if ( true === empty($s) ) {
			$s = $this->_s;
		}
		
		if ( true === empty($s) ) {
			return false;
		}

		if ( 1 === preg_match("/[^a-z]/i", $s) ) {
			return false;
		}
		return true;
	}	
}