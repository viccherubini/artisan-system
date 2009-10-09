<?php

/**
 * Static class that contains methods to validate alphabetic strings.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Empty {
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
		return empty($s);
	}	
}