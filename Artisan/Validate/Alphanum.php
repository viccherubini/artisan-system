<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * Static class that contains methods to validate alphanumeric strings.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Alphanum extends Artisan_Validate {
	private $_s = NULL;

	public function __construct($s = NULL) {
		$this->_s = trim($s);
	}

	/**
	 * Validates whether a submitted string is comprised of letters and digits only.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $s The string to test.
	 * @retval boolean True if the value is all alphanumeric, false otherwise.
	 */
	public function isValid($s) {
		if ( true === empty($s) ) {
			$s = $this->_s;
		}
		
		if ( true === empty($s) ) {
			return false;
		}

		if ( 1 === preg_match("/[^a-z0-9]/i", $s) ) {
			return false;
		}
		return true;
	}	
}