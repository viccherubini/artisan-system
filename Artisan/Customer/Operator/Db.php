<?php

/**
 * @see Artisan_Customer
 */
//require_once 'Artisan/Customer.php';
require_once 'Artisan/Customer/Operator.php';


require_once 'Artisan/User/Db.php';

class Artisan_Customer_Operator_Db extends Artisan_Operator {
	private $DB = NULL;
	private $USER = NULL;
	
	public function __construct(Artisan_Db &$DB) {
		//$this->DB = &$DB;
		$this->USER = new Artisan_User_Db($DB);
	}
	
	
	//public 
}