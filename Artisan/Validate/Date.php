<?php

/**
 * Static class that contains methods to validate date values.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Date {
	private $_date = NULL;

	public function __construct($date = NULL) {
		$this->_date = trim($date);
	}
	
	/**
	 * Validates whether a submitted string is a date.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $date The date to test.
	 * @retval boolean True if the value is a date, false otherwise.
	 */
	public function isValid($date = NULL) {
		
		// Potential Date Values:
		// yyyy-mm-dd hh:mm:ss
		// mm-dd-yyyy
		// dd-mm-yyyy
		
		if ( true === empty($date) ) {
			$date = $this->_date;
		}
		
		if ( true === empty($date) ) {
			return false;
		}

		// Enter code here
		
		return true;
	}	
}