<?php

/**
 * @see Artisan_Customer_Exception
 */
require_once 'Artisan/Customer/Exception.php';

require_once 'Artisan/Customer/Address.php';

/**
 * This class allows for the management of customers as through an e-commerce
 * or other type of customer management interface.
 * @author <vmc@leftnode.com>
 */
abstract class Artisan_Customer {
	/// The customer array after being loaded from the database and modified.
	protected $_cust = array();
	
	/// The customer array after being loaded from the database and not modified. This is kept in memory for faster lookups.
	protected $_custOrig = array();
	
	protected $_commentList = array();
	
	protected $_head = 0;
	
	
	protected $_addrList = array();
	
	/// Primary key
	protected $_customerId = 0;
	protected $_parentId = 0;
	
	protected $_fieldList = array();

	public $address = NULL;

	const REV_ADDED = 'A';
	const REV_MODIFIED = 'M';
	const REV_DELETED = 'D';
	const REV_HEAD = 'head';
	
	public function __construct() {
		$this->_cust = $this->_custOrig = array();
	}
	
	
	public function fromArray($cust) {
		if ( false === is_array($cust) ) {
			return false;
		}
		
		foreach ( $cust as $k => $v ) {
			$this->__set($k, $v);
		}
	}
	
	/**
	 * Checks to see if a value exists in the $_customer field. If not, then
	 * checks the $_customer_additional field. If not there, then returns NULL.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the variable to return from $_customer_additional.
	 * @retval string The specified value in $name or NULL if it's not found anywhere.
	 */
	public function __get($name) {
		if ( 'address' == $name ) {
			return false;
		}
		
		if ( true === asfw_exists($name, $this->_cust) ) {
			return $this->_cust[$name];
		}
		return NULL;
	}
	
	public function __set($name, $value) {
		$name = trim($name);

		// Do not allow the direct manipulation of the customer_id
		if ( 'customer_id' == $name ) {
			return false;
		}
	
		if ( true === isset($this->_fieldList[$name]) ) {
			$hook = $this->_fieldList[$name]['hook'];
			$regex = $this->_fieldList[$name]['valid_regex'];
			$maxlen = $this->_fieldList[$name]['maxlength'];
			
			if ( $maxlen > 0 ) {
				$value = substr($value, 0, $maxlen);
			}
			
			// Validate it against a regex
			$pass_regex = true;
			if ( false === empty($regex) ) {
				if ( 0 == preg_match($regex, $value) ) {
					$pass_regex = false;
				}
			}
			
			$pass_hook = true;
			if ( false === empty($hook) ) {
				$pass_hook = $hook($value);
			}
			
			if ( $pass_hook && $pass_regex ) {
				$this->_cust[$name] = $value;
			}
		}
		return true;
	}
	
	public function addComment($subject, $comment, $status) {
	
	}
	
	public function __unset($name) {
		if ( true === asfw_exists($name, $this->_cust) ) {
			unset($this->_cust[$name]);
		}
	}
	
	public function __clone() {
		$this->_parentId = $this->_customerId;
		$this->_customerId = 0;
	}
	
	public function getHead() {
		return $this->_head;
	}
	
	public function reset() {
		$this->_cust = $this->_custOrig = array();
		$this->_customerId = 0;
	}
}