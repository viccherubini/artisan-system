<?php

require_once 'Artisan/Customer.php';


class Artisan_Customer_Adapter_Db extends Artisan_Customer {
	/// Database instance passed into the class. Assumes the database already has a connection.
	private $_dbConn = NULL;
	
	/// An Artisan_Vo object that contains a list of tables to use.
	//private $_table_list = NULL;
	
	private $_customerTable = NULL;
	private $_commentHistoryTable = NULL;
	private $_fieldTable = NULL;
	private $_fieldTypeTable = NULL;
	private $_fieldValueTable = NULL;
	private $_historyTable = NULL;
	
	/// Ignore the customer_field* tables if true, avoiding the extra queries. Use the CONFIG to set this to false.
	//private $_ignore_field_tables = true;
	
	public function __construct(Artisan_Db $db, Artisan_Vo $tableList = NULL) {
		parent::__construct();
		
		$this->_dbConn = $db;
		
		if ( false === empty($tableList) ) {
			$this->setCustomerTable($tableList->customer);
			$this->setCommentHistoryTable($tableList->comment_history);
			$this->setFieldTable($tableList->field);
			$this->setFieldTypeTable($tableList->field_type);
			$this->setFieldValueTable($tableList->field_value);
			$this->setHistoryTable($tableList->history);
		}
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
	
	public function setFieldValueTable($table) {
		$this->_fieldValueTable = trim($table);
		return $this;
	}
	
	public function setHistoryTable($table) {
		$this->_historyTable = trim($table);
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
	 * @throw Artisan_User_Exception If the user can not be found.
	 * @retval boolean Returns true.
	 */
	public function load($customer_id, $revision = self::REV_HEAD) {
		try {
			$this->_load($customer_id, $revision);
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
	protected function _load($customer_id, $revision) {
		$this->_checkDb(__FUNCTION__);
		
		// Because the queries can be quite intensive, if the ignore_fields 
		// is set to true, don't bother looking in them, simply load the customer,
		// comment_history.
		$customer_id = intval($customer_id);
		
		if ( $customer_id < 1 ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Customer ID specified must be numeric and greater than 0.');
		}
		
		try {
			$result_customer = $this->_dbConn->select()
				->from($this->_customerTable)
				->where('customer_id = ?', $customer_id)
				->query();
			$row_count = $result_customer->numRows();
			if ( 1 != $row_count ) {
				throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'No customer with ID ' . $customer_id . ' found.');
			}
			
			$c_vo = $result_customer->fetchVo();
			unset($c_vo->customer_id);
			
			// This is cloned so that way $c_vo is not copied by reference.
			// That way, new values to $_user will not show up here and $_user_original
			// will be pristine for updating.
			$this->_customerOriginal = clone $c_vo;
			
			
			// See if there are any data to load up in the additional fields
			$cfv = asfw_create_table_alias($this->_fieldValueTable);
			$cf = asfw_create_table_alias($this->_fieldTable);
			
			$result_field = $this->_dbConn->select()
				->from($this->_fieldValueTable, $cfv, $cf.'.name', $cfv.'.value')
				->innerJoin($this->_fieldTable, $cfv.'.field_id', $cf.'.field_id')
				->where($cfv.'.customer_id = ?', $customer_id)
				->query();
			$row_count = $result_field->numRows();
			if ( $row_count > 0 ) {
				while ( $f = $result_field->fetchVo() ) {
					$this->_customerField->{$f->name} = $f->value;
				}
			}

			// If the revision isn't head, then, load up those values to overwrite the original values
			if ( $revision != self::REV_HEAD && true === is_int($revision) ) {
				$result_revision = $this->_dbConn->select()
					->from($this->_historyTable, asfw_create_table_alias($this->_historyTable), 'field', 'value', 'revision')
					->where('customer_id = ?', $customer_id)
					->where('revision = ?', $revision)
					->orderBy('history_id', 'ASC')
					->query();
				$row_count = $result_revision->numRows();
				if ( $row_count > 0 ) {
					while ( $rev = $result_revision->fetchVo() ) {
						if ( true === $c_vo->exists($rev->field) ) {
							$c_vo->{$rev->field} = $rev->value;
						}
						$this->_revision = $rev->revision;
					}
				}
			}
			
			// Get the reivison number
			/*
			$result_revision = $this->_dbConn->select()
				->from($this->_table_list->history)
				->where('customer_id = ?', $customer_id)
				->groupBy('revision')
				->orderBy('revision', 'DESC')
				->query();
			$row_count = $result_revision->numRows();
			if ( $row_count > 0 ) {
				$rev = $result_revision->fetchVo();
				$this->_rev = $rev->revision;
			}
			*/
		} catch ( Artisan_Db_Exception $e ) {
			throw $e;
		}

		$this->_customer = $c_vo;
		$this->_customerId = $customer_id;
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
		/*
		$curr_rev = ++$this->_revision_current;
		$history = array(
			'customer_id' => $this->_user_id,
			'date_create' => time(),
			'revision' => $curr_rev,
			'type' => NULL,
			'field' => NULL,
			'value' => NULL
		);
		
		
		// A diff needs to be done between the initial user data and the updated user data
		// A diff then needs to be done between the initial user_field data and the updated user_field data
		foreach ( $this->_user as $k => $v ) {
			$history['field'] = $k;
			$history['value'] = $v;
			$history['type'] = NULL;
			if ( false === $this->_user_original->exists($k) ) {
				$history['type'] = self::REV_ADDED;
				
				// These need to be added to the customer_field and customer_field_value tables
			} else {
				if ( $this->_user_original->$k != $v ) {
					$history['value'] = $this->_user_original->$k;	
					$history['type'] = self::REV_MODIFIED;
				}
			}
			
			if ( false === empty($history['type']) ) {
				$this->_dbConn->insert()
					->into($this->_table_list->history)
					->values($history)
					->query();
			}
		}
		
		// Now do the updates
		$this->_user->date_modify = time();
		$this->_dbConn->update()
			->table($this->_table_list->customer)
			->set($this->_user->toArray())
			->where('customer_id = ?', $this->_user_id)
			->query();
		*/
		
		
		
		
		
		
		
	}
	
	/**
	 * Ensures that a database connection exists.
	 * @author vmc <vmc@leftnode.com>
	 * @param $method The method this is being called from.
	 * @throw Artisan_User_Exception If the database connection does not exist.
	 * @retval boolean Returns true.
	 */
	private function _checkDb() {
		if ( false === $this->_dbConn->isConnected() ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
	
	
	private function _insertDiff($new_list, $orig_list) {
	
	}
	
}