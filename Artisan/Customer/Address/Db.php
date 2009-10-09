<?php

require_once 'Artisan/Customer/Address.php';

class Artisan_Customer_Address_Db extends Artisan_Customer_Address {
	/// Database instance passed into the class. Assumes the database already has a connection.
	private $_dbConn = NULL;
	
	private $_addrTable = 'customer_address';
	
	public function __construct(Artisan_Db $db, $addrTable = NULL) {
		parent::__construct();
		
		$this->_dbConn = $db;
		$this->setAddressTable($addrTable);
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
	}
	
	public function load($address_id) {
		$this->_checkDb(__FUNCTION__);
		
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
		unset($addr['address_id']);
		
		$this->_addr = $addr;
		$this->_addressId = $address_id;
	}
	
	public function write() {
		if ( $this->_addressId > 0 ) {
			$this->_update();
		} else {
			$this->_insert();
		}
		return $this->_addressId;
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
	}
	
	private function _update() {
		$this->_addr['date_modify'] = time();
		$this->_dbConn->update()->table($this->_addrTable)->set($this->_addr)->where('address_id = ?', $this->_addressId)->query();
		
	}
	
	/**
	 * Ensures that a database connection exists.
	 * @author vmc <vmc@leftnode.com>
	 * @param $method The method this is being called from.
	 * @throw Artisan_Customer_Exception If the database connection does not exist.
	 * @retval boolean Returns true.
	 */
	private function _checkDb() {
		if ( false === $this->_dbConn->isConnected() ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
}
