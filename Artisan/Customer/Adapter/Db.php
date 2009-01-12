<?php

require_once 'Artisan/Customer.php';


class Artisan_Customer_Adapter_Db extends Artisan_Customer {
	/// Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	/// An Artisan_Vo object that contains a list of tables to use.
	private $_table_list = NULL;
	
	/// Ignore the customer_field* tables if true, avoiding the extra queries. Use the CONFIG to set this to false.
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
		$this->_checkDb();
		
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
	public function load($customer_id, $revision = self::REV_HEAD) {
		try {
			$this->_revision_load = $revision;
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
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The Customer ID specified must be numeric and greater than 0.');
		}
		
		try {
			$result_customer = $this->DB->select()
				->from($this->_table_list->customer)
				->where('customer_id = ?', $customer_id)
				->query();
			$row_count = $result_customer->numRows();
			if ( 1 != $row_count ) {
				throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'No customer with ID ' . $customer_id . ' found.');
			}
			
			$c_vo = $result_customer->fetchVo();
			unset($c_vo->user_id);
			
			// See if there are any data to load up in the additional fields
			$cfv_table = $this->_table_list->field_value;
			$cf_table = $this->_table_list->field;
			$cfv = asfw_create_table_alias($cfv_table);
			$cf = asfw_create_table_alias($cf_table);
			
			$result_field = $this->DB->select()
				->from($cfv_table, $cfv, $cf.'.name', $cfv.'.value')
				->innerJoin($cf_table, $cfv.'.field_id', $cf.'.field_id')
				->where($cfv.'.customer_id = ?', $customer_id)
				->query();
			$row_count = $result_field->numRows();
			if ( $row_count > 0 ) {
				while ( $f = $result_field->fetchVo() ) {
					$this->_customer_additional->{$f->name} = $f->value;
				}
			}
			
			// This is cloned so that way $c_vo is not copied by reference.
			// That way, new values to $_user will not show up here and $_user_original
			// will be pristine for updating.
			$this->_user_original = clone $c_vo;

			// If the revision isn't head, then, load up those values
			if ( $this->_rev_load != self::REV_HEAD && true === is_int($this->_rev_load) ) {
				$result_revision = $this->DB->select()
					->from($this->_table_list->history, 'ch', 'field', 'value')
					->where('customer_id = ?', $customer_id)
					->where('revision = ?', $this->_revision_load)
					->query();
				$row_count = $result_revision->numRows();
				if ( $row_count > 0 ) {
					while ( $rev = $result_revision->fetchVo() ) {
						if ( true === $c_vo->exists($rev->field) ) {
							$c_vo->{$rev->field} = $rev->value;
						}
					}
				}
			}
			
			// Get the reivison number
			$result_revision = $this->DB->select()
				->from($this->_table_list->history)
				->where('customer_id = ?', $customer_id)
				->groupBy('revision')
				->orderBy('revision', 'DESC')
				->query();
			$row_count = $result_revision->numRows();
			if ( $row_count > 0 ) {
				$rev = $result_revision->fetchVo();
				$this->_revision_current = $rev->revision;
			}
		} catch ( Artisan_Db_Exception $e ) {
			throw $e;
		}

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
				$this->DB->insert()
					->into($this->_table_list->history)
					->values($history)
					->query();
			}
		}
		
		// Now do the updates
		$this->_user->date_modify = time();
		$this->DB->update()
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
		if ( false === $this->DB->isConnected() ) {
			throw new Artisan_Customer_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
	
	
	private function _insertDiff($new_list, $orig_list) {
	
	}
	
}