<?php

/**
 * @see Artisan_Customer
 */
//require_once 'Artisan/Customer.php';
require_once 'Artisan/Customer/Operator.php';

class Artisan_Customer_Operator_Db extends Artisan_Operator {
	private $DB = NULL;
	
	public function __construct(Artisan_Db &$DB) {
		$this->DB = &$DB;
	}
	
	
	//public 
}