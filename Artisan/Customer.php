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

	///< Current revision number
	protected $_revision = 0;
	
	///< Revision number to load
	protected $_rev_load = NULL;

	const REV_ADDED = 'A';
	const REV_MODIFIED = 'M';
	const REV_DELETED = 'D';

	const REV_HEAD = 'head';
	
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