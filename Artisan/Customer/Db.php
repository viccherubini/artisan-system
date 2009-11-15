<?php

/**
 * @see Artisan_Customer
 */
require_once 'Artisan/Customer.php';

require_once 'Artisan/Customer/Address/Db.php';

class Artisan_Customer_Db extends Artisan_Customer {
	/// Database instance passed into the class. Assumes the database already has a connection.
	private $_dbConn = NULL;
	
	private $_customerTable = 'customer';
	private $_commentHistoryTable = 'customer_comment_history';
	private $_fieldTable = 'customer_field';
	private $_fieldTypeTable = 'customer_field_type';
	private $_historyTable = 'customer_history';
	private $_addressTable = 'customer_address';

	public function __construct(Artisan_Db $db, Artisan_Vo $tableList = NULL) {
		parent::__construct();
		
		$this->_dbConn = $db;
		
		if ( false === empty($tableList) ) {
			$this->setCustomerTable($tableList->customer);
			$this->setCommentHistoryTable($tableList->comment_history);
			$this->setFieldTable($tableList->field);
			$this->setFieldTypeTable($tableList->field_type);
			$this->setHistoryTable($tableList->history);
			$this->setAddressTable($tableList->address);
		}
		
		$this->_loadFieldList();
	}
	
	
	public function setCustomerTable($table) {
		$this->_customerTable = trim($table);
		return $this;
	}
	
	public function setCommentHistoryTable($table) {
		$this->_commentHistoryTable = trim($table);
		return $this;
	}
	
	public function setFieldTable($table) {
		$this->_fieldTable = trim($table);
		return $this;
	}
	
	public function setFieldTypeTable($table) {
		$this->_fieldTypeTable = trim($table);
		return $this;
	}

	public function setHistoryTable($table) {
		$this->_historyTable = trim($table);
		return $this;
	}
	
	public function setAddressTable($table) {
		$this->_addressTable = trim($table);
		return $this;
	}

	/**
	 * Writes a user to the database. If the user exists, their data is updated,
	 * if they are new, their data is inserted.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int Returns the user ID of the newly created user or updated user.
	 */
	public function write() {
		$this->_checkDb();
		if ( $this->_customerId > 0 ) {
			$this->_update();
		} else {
			$this->_insert();
		}
		return $this->_customerId;
	}
	
	/**
	 * Loads a user from the database based on their user ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The ID of the user to load.
	 * @throw Artisan_Customer_Exception If the user can not be found.
	 * @retval boolean Returns true.
	 */
	public function load($customer_id, $revision = self::REV_HEAD) {
		try {
			$this->_load($customer_id, $revision);
			$this->_loadAddressList();
		} catch ( Artisan_Customer_Exception $e ) {
			throw $e;
		}
		return true;
	}
	
	private function _loadAddressList() {
		if ( $this->_customerId < 1 ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The customer has not been loaded yet.');
		}
		
		$customer_address_db = new Artisan_Customer_Address_Db($this->_dbConn);
		$customer_address_db->setCustomerId($this->_customerId);
		
		$result_address = $this->_dbConn->select()
			->from($this->_addressTable)
			->where('customer_id = ?', $this->_customerId)
			->where('status = 1')
			->query();
		$this->address = new Artisan_Db_Iterator($result_address, $customer_address_db);
		//$this->address = $this->address->obj();
	}
	
	/**
	 * Does the actual loading of the user from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The ID of the user to load.
	 * @throw Artisan_Customer_Exception If the user ID specified is not numeric or less than 0.
	 * @throw Artisan_Customer_Exception If the user can not be found in the database.
	 * @retval boolean Returns true.
	 */
	protected function _load($customer_id, $revision) {
		$this->_checkDb(__FUNCTION__);
		$customer_id = intval($customer_id);
		
		if ( $customer_id < 1 ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Customer ID specified must be numeric and greater than 0.');
		}
		
		// See if there is a parent_id to load up
		$result_cust = $this->_dbConn->select()
			->from($this->_customerTable)
			->where('customer_id = ?', $customer_id)
			->query();
		if ( $result_cust->numRows() < 1) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Customer with ID #' . $customer_id . ' does not exist.');
		}
		
		$customer = $result_cust->fetch();
		$this->_parentId = $customer['parent_id'];
		unset($customer);
		
		// Find the latest revision if $revision is the head
		$head = $this->_findHead($customer_id);
		if ( self::REV_HEAD === $revision ) {
			$revision = $head;
		}
		
		$cust = array();
		$result_rev = $this->_dbConn->select()
			->from($this->_historyTable)
			->where('customer_id = ?', $customer_id)
			->where('revision <= ?', $revision)
			->orderBy('history_id', 'ASC')
			->query();
		while ( $rev = $result_rev->fetch() ) {
			$cust[$rev['field']] = $rev['value'];
			if ( $rev['type'] == self::REV_DELETED ) {
				unset($cust[$rev['field']]);
			}
		}

		$this->_cust = $cust;
		$this->_custOrig = $cust;
		$this->_customerId = $customer_id;

		return true;
	}
	
	private function _loadFieldList() {
		$field_list = array();
		$result_field = $this->_dbConn->select()
				->from($this->_fieldTable, 'cf', 'cf.name', 'cft.maxlength', 'cft.valid_regex', 'cft.hook')
				->innerJoin($this->_fieldTypeTable, 'cf.type_id', 'cft.type_id')
				->where('status = 1')
				->query();
		while ( $f = $result_field->fetch() ) {
			$name = $f['name']; unset($f['name']);
			$field_list[$name] = $f;
		}
		$this->_fieldList = $field_list;
		return true;
	}
	
	/**
	 * After write() is called, if the user is to be inserted, this method is called
	 * to insert the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception If the INSERT query fails.
	 * @retval boolean Returns true.
	 */
	protected function _insert() {
		// Create the initial customer record
		$time = time();
		$cust = array(
			'customer_id' => NULL,
			'parent_id' => $this->_parentId,
			'date_create' => $time,
			'date_modify' => NULL,
			'status' => 1
		);
		
		$this->_dbConn->insert()
			->into($this->_customerTable)
			->values($cust)
			->query();
		$customer_id = $this->_dbConn->insertId();
		
		if ( $customer_id > 0 ) {
			$c = $this->_cust;
			$rev = array(
				'customer_id' => $customer_id,
				'date_create' => $time,
				'revision' => 1,
				'type' => self::REV_ADDED
			);
			foreach ( $c as $f => $v ) {
				$rev['field'] = $f;
				$rev['value'] = $v;
				$this->_dbConn->insert()
					->into($this->_historyTable)
					->values($rev)
					->query();
			}
			$this->_customerId = $customer_id;
			$this->_head = 1;
			$this->_custOrig = $this->_cust;
		}
		
		return $customer_id;
	}
	
	/**
	 * After update() is called, if the user is to be updated, this method is called
	 * to update the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception If the INSERT query fails.
	 * @retval boolean Returns true.
	 */
	protected function _update() {
		$rev = ++$this->_head;
		$added = array_diff_key($this->_cust, $this->_custOrig);
		$deleted = array_diff_key($this->_custOrig, $this->_cust);
		
		$modified = array_diff_assoc($this->_cust, $this->_custOrig);
		$modified = array_diff_assoc($modified, $added);

		$this->_insertDiff($added, self::REV_ADDED, $rev);
		$this->_insertDiff($modified, self::REV_MODIFIED, $rev);
		$this->_insertDiff($deleted, self::REV_DELETED, $rev);
		
		$this->_dbConn->update()
			->table($this->_customerTable)
			->set(array('date_modify' => time()))
			->query();
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
	
	
	private function _insertDiff($list, $type, $revision) {
		if ( false === is_array($list) || count($list) < 1 ) {
			return false;
		}
		if ( $revision < 1 ) {
			return false;
		}
		
		$history = array(
			'customer_id' => $this->_customerId,
			'date_create' => time(),
			'revision' => $revision,
			'type' => $type
		);
		foreach ( $list as $k => $v ) {
			$history['field'] = $k;
			$history['value'] = $v;
			$this->_dbConn->insert()
				->into($this->_historyTable)
				->values($history)
				->query();
		}
		return true;
	}
	
	
	private function _findHead($customer_id) {
		$result_revision = $this->_dbConn->select()
			->from($this->_historyTable)
			->where('customer_id = ?', $customer_id)
			->groupBy('revision')
			->orderBy('revision', 'DESC')
			->limit(1)
			->query();
		if ( 1 == $result_revision->numRows() ) {
			$revision = $result_revision->fetch('revision');
			$this->_head = $revision;
		}
		return $this->_head;
	}
}
