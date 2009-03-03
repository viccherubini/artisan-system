<?php

require_once 'Artisan/Interface/Iterable.php';

class Artisan_Customer_Address implements Artisan_Interface_Iterable {
	/// Database instance passed into the class. Assumes the database already has a connection.
	private $_dbConn = NULL;
	
	private $_addrTable = 'customer_address';
	
	private $_addressId = 0;
	
	private $_addr = array();
	
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

	public function setDb(Artisan_Db $dbConn) {
		_asfw_check_db($dbConn);
		$this->_dbConn = $dbConn;
		return $this;
	}

	public function setAddressTable($addrTable) {
		$addrTable = trim($addrTable);
		if ( false === empty($addrTable) ) {
			$this->_addrTable = $addrTable;
		}
		return $this;
	}
	
	public function setCustomerId($customer_id) {
		$customer_id = intval($customer_id);
		if ( $customer_id > 0 ) {
			$this->__set('customer_id', $customer_id);
		}
		return $this;
	}

	public function write() {
		if ( $this->_addressId > 0 ) {
			$this->_update();
		} else {
			$this->_insert();
		}
		return $this->_addressId;
	}
	
	/**
	 * Checks to see if a value exists in the $_addr field. If not there, then returns NULL.
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
	
	/**
	 * Sets a value in the $_addr array.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the attribute to set.
	 * @param $value The actual value to set.
	 * @retval boolean Returns true.
	 */
	public function __set($name, $value) {
		if ( 'address_id' == $name ) {
			return false;
		}
		
		$this->_addr[$name] = $value;
		return true;
	}
	
	/**
	 * The loadFromArray() method comes from the Artisan_Interface_Iterable interface
	 * and requires that an object have its data loaded from an array.
	 * @author vmc <vmc@leftnode.com>
	 * @param $address The address array data to load from.
	 * @retval boolean Returns true if the address was successfully loaded, false otherwise.
	 */
	public function loadFromArray($address) {
		if ( true === asfw_exists('address_id', $address) ) {
			$this->_addressId = $address['address_id'];
		}
		$this->_addr = $address;
		return true;
	}
	
	
	private function _load($address_id) {
		$address_id = intval($address_id);
		if ( $address_id < 1 ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Address ID specified must be numeric and greater than 0.');
		}
		
		// See if there is a parent_id to load up
		$result_addr = $this->_dbConn->select()
			->from($this->_addrTable)
			->where('address_id = ?', $address_id)
			->query();
		if ( $result_addr->numRows() < 1) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Customer Address with ID #' . $address_id . ' does not exist.');
		}
		
		$addr = $result_addr->fetch();
		$this->loadFromArray($addr);
	}
	
	private function _insert() {
		$addr = array(
			'address_id' => NULL,
			'date_create' => time(),
			'date_modify' => NULL,
			'status' => 1
		);
		$addr = array_merge($addr, $this->_addr);
		$this->_dbConn->insert()
			->into($this->_addrTable)
			->values($addr)
			->query();
		$address_id = $this->_dbConn->insertId();
		
		if ( $address_id < 0 ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'Failed to create the address.');
		}
		$this->_addressId = $address_id;
		return true;
	}
	
	private function _update() {
		$this->_addr['date_modify'] = time();
		$this->_dbConn->update()
			->table($this->_addrTable)
			->set($this->_addr)
			->where('address_id = ?', $this->_addressId)
			->query();
		return true;
	}
}