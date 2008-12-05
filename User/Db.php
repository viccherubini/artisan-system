<?php

/**
 * @see Artisan_User
 */
require_once 'Artisan/User.php';

/**
 * @see Artisan_User_Exception
 */
require_once 'Artisan/User/Exception.php';

/**
 * This class manipulates all of the user data in the scope of a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Database extends Artisan_User {
	///< Database instance passed into the class. Assumes the database already has a connection.
	private $DB = NULL;
	
	///< The name of the table that holds user data, this is also defined in Artisan/Auth/Database.php!
	const TABLE_USER = 'artisan_user';
	
	/**
	 * Constructor for the Artisan_User class to get users from the database. Assumes
	 * the object is already connected to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB Database object that already has a connection.
	 * @retval Object The new Artisan_User_Database object.
	 */
	public function __construct(Artisan_Db &$DB) {
		$this->DB = &$DB;
	}
	
	/**
	 * Writes a user to the database. If the user exists, their data is updated,
	 * if they are new, their data is inserted.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Implement this!
	 * @retval boolean Returns true.
	 */
	public function write() {
	
		return true;
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
		$user_id = intval($user_id);
		
		if ( $user_id < 1 ) {
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'The user ID specified must be numeric and greater than 0', __CLASS__, __FUNCTION__);
		}
		
		$result_user = $this->DB->select()
			->from(self::TABLE_USER, asfw_create_table_alias(self::TABLE_USER))
			->where(array('user_id = ?', $user_id))
			->query();
		$row_count = $result_user->numRows();
		
		if ( 1 !== $row_count ) {
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'No user found with ID ' . $user_id . '.', __CLASS__, __FUNCTION__);
		}
		
		// Now that we have a user, load up their data
		$user_data = $result_user->fetch();
		unset($result_user);

		$user_data = new Artisan_VO($user_data);
		$this->setUserId($user_id);
		$this->setUserName($user_data->user_name);
		$this->setUserPassword($user_data->user_password);
		$this->setUserPasswordSalt($user_data->user_password_salt);
		$this->setUserEmailAddress($user_data->user_email_address);
		$this->setUserFirstname($user_data->user_firstname);
		$this->setUserMiddlename($user_data->user_middlename);
		$this->setUserLastname($user_data->user_lastname);
		$this->setUserStatus($user_data->user_status);
	}
	
	/**
	 * After write() is called, if the user is to be inserted, this method is called
	 * to insert the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Implement this!
	 * @retval boolean Returns true.
	 */
	protected function _insert() {
		return true;
	}
	
	/**
	 * After update() is called, if the user is to be updated, this method is called
	 * to update the user data.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Implement this!
	 * @retval boolean Returns true.
	 */
	protected function _update() {
		return true;
	}
	
	/**
	 * Creates a Value Object of the user data to be inserted into the database.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Implement this!
	 * @retval boolean Returns true.
	 */
	protected function _makeRecord() {
		return true;
	}
}
