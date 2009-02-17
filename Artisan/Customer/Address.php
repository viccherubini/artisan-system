<?php

require_once 'Artisan/Interface/Iterable.php';

class Artisan_Customer_Address implements Artisan_Interface_Iterable {
	protected $_addressId = 0;
	protected $_addr = array();
	
	/**
	 * Builds a new Artisan_Customer_Address object. If no address_id is specified
	 * the address will be empty and will be inserted when written, otherwise, it will
	 * be updated (if the address_id exists, of course).
	 * @author vmc <vmc@leftnode.com>
	 * @param $address_id If greater than 0, the related address is loaded.
	 * @retval Object Returns new Artisan_Customer_Address object.
	 */
	public function __construct($address_id = 0) {
		$address_id = intval($address_id);
		if ( $address_id > 0 ) {
			$this->_load($address_id);
		}
	}
	
	public function fromArray($addr) {
		if ( false === is_array($addr) ) {
			return false;
		}

		foreach ( $addr as $k => $v ) {
			$this->__set($k, $v);
		}
		return $this;
	}
	
	/**
	 * Checks to see if a value exists in the $_customer field. If not, then
	 * checks the $_customer_additional field. If not there, then returns NULL.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the variable to return from $_customer_additional.
	 * @retval string The specified value in $name or NULL if it's not found anywhere.
	 */
	public function __get($name) {
		if ( true === asfw_exists($name, $this->_addr) ) {
			return $this->_addr[$name];
		}
		return NULL;
	}
	
	public function __set($name, $value) {
		if ( 'address_id' == $name ) {
			return false;
		}
		
		$this->_addr[$name] = $value;
		return true;
	}
	
	/**
	 * The loadFromArray() method comes from the Artisan_Interface_Iterable interface
	 * and requires that an object have its data loaded from an array. The array
	 * will be required to have the primary key in it. If not, the load will fail
	 * and the object will remain stagnant.
	 * @author vmc <vmc@leftnode.com>
	 * @param $address The address array data to load from.
	 * @retval boolean Returns true if the address was successfully loaded, false otherwise.
	 */
	public function loadFromArray($address) {
		if ( false === asfw_exists('address_id', $address) ) {
			return false;
		}
		$this->_addr = $address;
		$this->_addressId = $address['address_id'];
	}

	public function __toString() {
		//return asfw_print_r($this, true);
	}

}