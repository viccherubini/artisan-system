<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

/**
 * This class allows for the management of customers as through an e-commerce
 * or other type of customer management interface.
 * @author <vmc@leftnode.com>
 */
class Artisan_Customer extends Artisan_User {
	private $OP;
	
	
	private $_customer_id = 0;
	
	
	public function __construct($customer_id = 0) {
		
	}
	/*
	public function write() { }
	
	protected function _insert();
	protected function _update();
	protected function _load($customer_id);
	*/
}