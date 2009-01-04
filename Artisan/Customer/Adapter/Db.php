<?php

require_once 'Artisan/Customer.php';


class Artisan_Customer_Adapter_Db extends Artisan_Customer {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	private $_table_list = NULL;
	
	///< Ignore the customer_field* tables if true, avoiding the extra queries. Use the CONFIG to set this to false.
	private $_ignore_field_tables = true;
	
	public function __construct(Artisan_Config &$CONFIG) {
		parent::__construct();
		
		if ( true === $CONFIG->exists('db_adapter') ) {
			$this->DB = &$CONFIG->db_adapter;
		}
		
		$this->_table_list = $CONFIG->table_list;
		if ( true === $CONFIG->exists('ignore_field_tables') ) {
			$this->_ignore_field_tables = $CONFIG->ignore_field_tables;
		}
		
		if ( $CONFIG->customer_id > 0 ) {
			$this->load($CONFIG->customer_id);
		}
	}
	
	/**
	 * Writes a user to the database. If the user exists, their data is updated,
	 * if they are new, their data is inserted.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the user ID of the newly created user or updated user.
	 */
	public function write() {
		$this->_checkDb(__FUNCTION__);
		
		if ( $this->_user_id > 0 ) {
			$this->_update();
		} else {
			$this->_insert();
		}
		
		return $this->_user_id;
	}
	
	/**
	 * Loads a user from the database based on their user ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The ID of the user to load.
	 * @throw Artisan_User_Exception If the user can not be found.
	 * @retval boolean Returns true.
	 */
	public function load($customer_id) {
		try {
			$this->_load($customer_id);
		} catch ( Artisan_Customer_Exception $e ) {
			throw $e;
		}
		return true;
	}
	
	/**
	 * Does the actual loading of the user from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The ID of the user to load.
	 * @throw Artisan_User_Exception If the user ID specified is not numeric or less than 0.
	 * @throw Artisan_User_Exception If the user can not be found in the database.
	 * @retval boolean Returns true.
	 */
	protected function _load($customer_id) {
		$this->_checkDb(__FUNCTION__);
		
		// Because the queries can be quite intensive, if the ignore_fields 
		// is set to true, don't bother looking in them, simply load the customer,
		// comment_history.
		$customer_id = intval($customer_id);
		
		if ( $customer_id < 1 ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Customer ID specified must be numeric and greater than 0.', __CLASS__, __FUNCTION__);
		}
		
		$result_customer = $this->DB->select()
			->from($this->_table_list->customer)
			->where('customer_id = ?', $customer_id)
			->query();
		$row_count = $result_customer->numRows();
		if ( 1 != $row_count ) {
			
		}

		$c_vo = $result_customer->fetchVo();
		unset($c_vo->user_id);
		
		// $_user and $_user_id are a part of Artisan_User
		$this->_user = $c_vo;
		$this->_user_id = $customer_id;
	}
	
	/**
	 * After write() is called, if the user is to be inserted, this method is called
	 * to insert the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception If the INSERT query fails.
	 * @retval boolean Returns true.
	 */
	protected function _insert() {
		echo __FUNCTION__;
	}
	
	/**
	 * After update() is called, if the user is to be updated, this method is called
	 * to update the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception If the INSERT query fails.
	 * @retval boolean Returns true.
	 */
	protected function _update() {
		echo __FUNCTION__;
	}
	
	/**
	 * Ensures that a database connection exists.
	 * @author vmc <vmc@leftnode.com>
	 * @param $method The method this is being called from.
	 * @throw Artisan_User_Exception If the database connection does not exist.
	 * @retval boolean Returns true.
	 */
	private function _checkDb($method) {
		if ( false === $this->DB->isConnected() ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The database does not have an active connection.', __CLASS__, $method);
		}
		return true;
	}
	
	
	
	
}