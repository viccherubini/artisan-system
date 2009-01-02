<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * Static class that contains methods to validate if a value is within a specified range.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Between extends Artisan_Validate {
	private $_var = NULL;

	public function __construct($var = NULL) {
		$this->_var = trim($var);
	}

	/**
	 * 	Validates if a number or character is between a range of numbers or characters.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $var The value to test.
	 * @param $low The low end of the range to match.
	 * @param $high The high end of the range to match.
	 * @param $inclusive Boolean to include or exclude $low/$high in range.
	 * @retval boolean True if the value is a credit card number, false otherwise.
	 */
	public function isValid($var, $low, $high, $inclusive = true) {
		if ( true === empty($var) ) {
			$var = $this->_var;
		}
		
		if ( true === empty($var) ) {
			return false;
		}

		if ( false === is_numeric($var) ) {
			$var = ord($var);
		}
		
		if ( false === is_numeric($low) ) {
			$low = ord($low);
		}
		
		if (false === is_numeric($high) ) {
			$high = ord($high);
		}

		if ( true === $inclusive ) {
			if ( $low <= $var && $high >= $var) {
				return true;
			}
		} else {
			if ( $low < $var && $high > $var) {
				return true;
			}
		}
		return false;
	}	
}