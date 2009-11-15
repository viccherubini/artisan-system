<?php

/**
 * Static class that contains methods to validate alphabetic strings.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Validate_Email {
	private $_e = NULL;

	public function __construct($e = NULL) {
		$this->_e = trim($e);
	}
	
	/**
	 * Validates whether a submitted string is comprised of letters only.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $s The string to test.
	 * @retval boolean True if the value is all alphabetic, false otherwise.
	 */
	public function isValid($e = NULL) {
		if ( true === empty($e) ) {
			$e = $this->_e;
		}
		
		if ( true === empty($e) ) {
			return false;
		}

		$e = trim($e);
		if ( 0 === preg_match("/([a-z0-9-_.!#$%^&*~`]+)(@[a-z0-9-]+\.[a-z]+)/i", $e) ) {
			return false;
		}
		return true;
	}	
}