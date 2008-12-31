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
class Artisan_Validate_Between extends Artisan_Validate {
	/**
	 * Validates whether a submitted value is between a high and low value.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $var The value to test.
	 * @param $low The low end of the range to match.
	 * @param $high The high end of the range to match.
	 * @param $inclusive Boolean to include or exclude $low/$high in range.
	 * @retval boolean True if the value is a credit card number, false otherwise.
	 */
	public static function isValid($var, $low, $high, $inclusive = true) {
		$var = ord($var);
		$low = ord($low);
		$high = ord($high);
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