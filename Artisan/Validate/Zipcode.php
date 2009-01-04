<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

class Artisan_Validate_Zipcode extends Artisan_Validate {
	private $_zip = NULL;

	public function __construct($zip = NULL) {
		$this->_zip = trim($zip);
	}

	public function isValid($zip = NULL) {
		if ( true === empty($zip) ) {
			$zip = $this->_zip;
		}
		
		if ( true === empty($zip) ) {
			return false;
		}

		// This only does US Zip Codes
		
		if ( 0 == preg_match('/^(\d{5})$/', $zip) && 0 == preg_match('/^(\d{5}-\d{4})$/', $zip) ) {
			return false;
		}
		
		return true;
	}
}