<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

require_once 'Artisan/Customer/Exception.php';

/**
 * This class allows for the management of customers as through an e-commerce
 * or other type of customer management interface.
 * @author <vmc@leftnode.com>
 */
abstract class Artisan_Customer extends Artisan_User {
	protected $_initial = NULL;

	protected $_revision = 1;

	public function __construct() {
		parent::__construct();
		
		$this->_initial = new Artisan_Vo();
	}
	
	
	
	
	/*
	public function write() { }
	
	protected function _insert();
	protected function _update();
	protected function _load($customer_id);
	*/
}