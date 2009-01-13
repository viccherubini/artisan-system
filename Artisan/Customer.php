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
abstract class Artisan_Customer {
	protected $_cust = NULL;
	protected $_custOrig = NULL;
	
	protected $_custAddl = NULL;
	protected $_custAddlOrig = NULL;


	///< Current revision number
	protected $_revision = 0;
	
	///< Primary key
	protected $_customerId = 0;




	
	const REV_ADDED = 'A';
	const REV_MODIFIED = 'M';
	const REV_DELETED = 'D';

	const REV_HEAD = 'head';
	
	public function __construct() {
		$this->_cust = new Artisan_Vo();
		$this->_custOrig = new Artisan_Vo;
		$this->_custAddl = new Artisan_Vo();
		$this->_custAddlOrig = new Artisan_Vo();
	}
	
	/**
	 * Checks to see if a value exists in the $_customer field. If not, then
	 * checks the $_customer_additional field. If not there, then returns NULL.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the variable to return from $_customer_additional.
	 * @retval string The specified value in $name or NULL if it's not found anywhere.
	 */
	public function __get($name) {
		if ( true === $this->_cust->exists($name) ) {
			return $this->_cust->$name;
		}
		if ( true === $this->_custAddl->exists($name) ) {
			return $this->_custAddl->$name;
		}
		return NULL;
	}
	
	public function __set($name, $value) {
		$name = trim($name);
		// If a new field is added, it should always go to the
		// $_customer_additional variable
		if ( true === $this->_cust->exists($name) ) {
			$this->_cust->$name = $value;
		} else {
			$this->_custAddl->$name = $value;
		}
		return true;
	}
	
	public function getRevision() {
		return $this->_revision;
	}
}