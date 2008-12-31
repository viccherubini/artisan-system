<?php

/**
 * @see Artisan_Validate
 */
require_once 'Artisan/Validate.php';

class Artisan_Validate_Ssn extends Artisan_Validate {
	private $_ssn = NULL;
	
	//Maybe one day...
	/*
	private $_area_nums = array(
		'001' => true, '002' => true, '003' => true,
		'004' => true, '005' => true, '006' => true,
		'007' => true, '008' => true, '009' => true,
		'010' => true, '011' => true, '012' => true,
		'013' => true, '014' => true, '015' => true,
		'016' => true, '017' => true, '018' => true,
		'019' => true, '020' => true, '021' => true,
		'022' => true, '023' => true, '024' => true,
		'025' => true, '026' => true, '027' => true,
		'028' => true, '029' => true, '030' => true,
		'031' => true, '032' => true, '033' => true,
		'034' => true, '035' => true, '036' => true,
		'037' => true, '038' => true, '039' => true,
		'040' => true, '041' => true, '042' => true,
		'043' => true, '044' => true, '045' => true,
		'046' => true, '047' => true, '048' => true,
		'049' => true, '050' => true, '051' => true,
		'052' => true, '053' => true, '054' => true,
		'055' => true, '056' => true, '057' => true,
		'058' => true, '059' => true, '060' => true,
		'061' => true, '062' => true, '063' => true,
		'064' => true, '065' => true, '066' => true,
		'067' => true, '068' => true, '069' => true,
		'070' => true, '071' => true, '072' => true
	);
	*/
	
	public function __construct($ssn = NULL) {
		$this->_ssn = trim($ssn);
	}

	public function isValid($ssn = NULL) {
		if ( true === empty($ssn) ) {
			$ssn = $this->_ssn;
		}
		
		if ( true === empty($ssn) ) {
			return false;
		}

		// Format must be XXX-XX-XXXX
		$ssn = trim($ssn);
		if ( 0 == preg_match('/(\d{3}-\d{2}-\d{4})$/', $ssn) ) {
			return false;
		}
		
		if ( '000-00-0000' == $ssn ) {
			return false;
		}
		
		$ssn_bits = explode('-', $ssn);
		$ssn_bits[0] = intval($ssn_bits[0]);
		
		if ( $ssn_bits[0] >= 740 ) {
			return false;
		}
		
		return true;
	}
}
