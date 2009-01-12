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
	protected $_user_original = NULL;


	protected $_customer_additional = NULL;

	///< Current revision number
	protected $_revision_current = 0;
	
	///< Revision number to load
	protected $_revision_load = NULL;

	protected $_customer_field = NULL;


	///< Primary key
	protected $_customer_id = 0;





	const REV_ADDED = 'A';
	const REV_MODIFIED = 'M';
	const REV_DELETED = 'D';

	const REV_HEAD = 'head';
	
	public function __construct() {
		parent::__construct();
		
		$this->_initial = new Artisan_Vo();
		$this->_customer_additional = new Artisan_Vo();
	}
	
	/**
	 * Magic method to get extra values from the field_value table.
	 * Thus, if the value doesn't exist in the base $_user variable,
	 * the $_customer_additional variable is checked for a value.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the variable to return from $_customer_additional.
	 * @retval string The initial value from Artisan_User::$_user, the value from $_customer_additional,
	 *                or NULL if it can not be found.
	 */
	public function __get($name) {
		$v = parent::__get($name);
		if ( false === empty($v) ) {
			return $v;
		}
		if ( true === $this->_customer_additional->exists($name) ) {
			return $this->_customer_additional->$name;
		}
		return NULL;
	}
	
	public function __set($name, $value) {
		$name = trim($name);
		// If a new field is added, it should always go to the
		// $_customer_additional variable
		
	}
}