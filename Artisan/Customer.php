<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

/**
 * This class allows for the management of customers as through an e-commerce
 * or other type of customer management interface. This class will be built and 
 * extends nothing. After it's built, an operator needs to be built and added to this
 * class. Because each operator extends an Artisan_User class, they all have 
 * overridden methods for writing the user data.
 * @author <vmc@leftnode.com>
 */
class Artisan_Customer {
	private $OP;
	
	
	private $_customer_id = 0;
	
	
	//public function __construct($customer_id = 0) {
	//	
	//}
	
	public function setOperator(Artisan_Customer_Operator &$OP) {
	
	}
	
	public function getCustomerId() {
		return $this->_user_id;
	}
	
	// all we need here are write();
	// and load();
}