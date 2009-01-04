<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

require_once 'Artisan/Functions/Database.php';

/**
 * This class manipulates all of the user data in the scope of a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Db extends Artisan_User {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	///< The name of the table that holds user data, this is also defined in Artisan/Auth/Database.php!
	const TABLE_USER = 'artisan_user';
	
	/**
	 * Constructor for the Artisan_User class to get users from the database. Assumes
	 * the object is already connected to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB Database object that already has an active connection.
	 * @param $user_id Optional, the ID of the user to load.
	 * @retval Object The new Artisan_User_Database object.
	 */
	public function __construct(Artisan_Db &$DB, $user_id = 0) {
		parent::__construct();
		$this->DB = &$DB;
		if ( $user_id > 0 ) {
			$this->load($user_id);
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
	public function load($user_id) {
		try {
			$this->_load($user_id);
		} catch ( Artisan_User_Exception $e ) {
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
	protected function _load($user_id) {
		$this->_checkDb();
		$user_id = intval($user_id);
		
		if ( $user_id < 1 ) {
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'The user ID specified must be numeric and greater than 0.');
		}
		
		$result_user = $this->DB->select()
			->from(self::TABLE_USER, asfw_create_table_alias(self::TABLE_USER))
			->where('user_id = ?', $user_id)
			->query();
		$row_count = $result_user->numRows();
		
		if ( 1 !== $row_count ) {
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'No user found with ID ' . $user_id . '.');
		}
		
		// Now that we have a user, load up their data
		$user_vo = $result_user->fetchVo();
		unset($user_vo->user_id);
		$this->_user = $user_vo;
		$this->_user_id = $user_id;
	}
	
	/**
	 * After write() is called, if the user is to be inserted, this method is called
	 * to insert the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception If the INSERT query fails.
	 * @retval boolean Returns true.
	 */
	protected function _insert() {
		if ( $this->_user_id <= 0 ) {
			$user_array = $this->_user->toArray();
			
			try {
				$this->DB->insert()
					->into(self::TABLE_USER)
					->values($user_array)
					->query();
				$this->_user_id = $this->DB->insertId();
			} catch ( Artisan_Db_Exception $e ) {
				throw $e;
			}
		}
	}
	
	/**
	 * After update() is called, if the user is to be updated, this method is called
	 * to update the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Db_Exception If the INSERT query fails.
	 * @retval boolean Returns true.
	 */
	protected function _update() {
		if ( $this->_user_id > 0 ) {
			$user_array = $this->_user->toArray();
			
			try {
				$this->DB->update()
					->table(self::TABLE_USER)
					->set($user_array)
					->where('user_id = ?', $this->_user_id)
					->query();
			} catch ( Artisan_Db_Exception $e ) {
				throw $e;
			}
		}
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
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
}