<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

/**
 * Static class that contains methods to validate credit card numbers.
 * @author rafshar <rafshar@gmail.com>
 */
class Artisan_Validate_Cc extends Artisan_Validate {
	private $_number = NULL;

	public function __construct($number = NULL) {
		$this->_number = trim($number);
	}

	/**
	 * Validates whether a submitted credit card number is valid or not using
	 * the Luhn algorithm.
	 * @author rafshar <rafshar@gmail.com>
	 * @param $number The credit card number to test.
	 * @retval boolean True if the value is a credit card number, false otherwise.
	 */
	public function isValid($number = NULL) {
		if ( true === empty($number) ) {
			$number = $this->_number;
		}

		$number = preg_replace("/\D/", "", $number);

		if ( true === empty($number) ) {
			return false;
		}

		$num_length =  strlen($number);
		$double_number = false;
		
		for ( $i = $num_length - 1; $i >= 0; $i-- ) {
			if ( true === $double_number ) {
				$sum += $number[$i] * 2;
				if ( 4 < $number[$i] ) {
					$sum -= 9;
				}
			} else {
				$sum += $number[$i];
			}
			$double_number = !$double_number;
		}
		return $sum % 10 == 0;
	}	
}