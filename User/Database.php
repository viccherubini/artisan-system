<?php

/**
 * This class manipulates all of the user data in the scope of a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_User_Database extends Artisan_User {
	
	private $DB = NULL;
	
	///< The name of the table that holds user data, this is also defined in Artisan/Auth/Database.php!
	const TABLE_USER = 'artisan_user';
	
	public function __construct(Artisan_Database &$db) {
		$this->DB = &$db;
	}
	
	public function write() {
	
	}
	
	public function load($user_id) {
		try {
			$this->_load($user_id);
		} catch ( Artisan_User_Exception $e ) {
			throw $e;
		}
	}
	
	protected function _load($user_id) {
		$user_id = intval($user_id);
		
		if ( $user_id < 1 ) {
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'The user ID specified must be numeric and greater than 0', __CLASS__, __FUNCTION__);
		}
		
		$this->DB->select
			->from(self::TABLE_USER, asfw_create_table_alias(self::TABLE_USER))
			->where(array('user_id' => $user_id))
			->query();
		$row_count = $this->DB->select->numRows();
		
		if ( 1 !== $row_count ) {
			throw new Artisan_User_Exception(ARTISAN_WARNING, 'No user found with ID ' . $user_id . '.', __CLASS__, __FUNCTION__);
		}
		
		// Now that we have a user, load up their data
		$user_data = $this->DB->select->fetch();
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
	
	protected function _insert() {
	
	}
	
	protected function _update() {
	
	}
	
	protected function _makeRecord() {
	
	}
}
