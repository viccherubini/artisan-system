<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * @see Artisan_Validate_Exception
 */
require_once 'Artisan/Validate/Exception.php';

/**
 * Static class that contains methods to validate credit cart numbers.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Alphanum extends Artisan_Validate {
	/**
	 * Validates whether a submitted string is comprised of letters and digits only.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $s The string to test.
	 * @retval boolean True if the value is all alphanumeric, false otherwise.
	 */
	public static function isValid($s) {
		if ( 1 === preg_match("/[^A-Za-z0-9]/", $s) ) {
			return false;
		}
		return true;
	}	
}