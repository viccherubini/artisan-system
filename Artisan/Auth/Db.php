<?php

/**
 * @see Artisan_Auth
 */
require_once 'Artisan/Auth.php';

require_once 'Artisan/Functions/Encryption.php';
require_once 'Artisan/Functions/Array.php';

/**
 * This class authenticates a user against a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Auth_Db extends Artisan_Auth {
	///< The database connection instance.
	private $_dbConn = NULL;
	
	private $_tableName = 'artisan_user';
	
	private $_userField = 'user_name';
	
	private $_passwordField = 'user_password';
	
	private $_passwordSaltField = 'user_password_salt';
	
	private $_idField = 'user_id';
	
	/**
	 * Default constructor to authenticate someone against a database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $DB A database object instance that already has a valid connection.
	 * @retval Object New Artisan_Auth_Database instance.
	 */
	public function __construct(Artisan_Db &$dbConn, $tableName = NULL, $userField = NULL, $passwordField = NULL, $passwordSaltField = NULL, $idField = NULL) {
		// We can only assume the database has a current connection
		// as we don't want to attempt to connect.
		$this->_dbConn = &$dbConn;
		
		$this->setTableName($tableName);
		$this->setUserField($userField);
		$this->setPasswordField($passwordField);
		$this->setPasswordSaltField($passwordSaltField);
		$this->setIdField($idField);
	}
	
	/**
	 * Sets the name of the table to load up the user from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $tableName The name of the table to set. Will not be set if the value is empty.
	 * @retval boolean Returns true.
	 */
	public function setTableName($tableName) {
		$tableName = trim($tableName);
		if ( false === empty($tableName) ) {
			$this->_tableName = $tableName;
		}
		return true;
	}
	
	/**
	 * Sets the name of the user_name field to load up the user from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $userField The name of the user_name field to set. Will not be set if the value is empty.
	 * @retval boolean Returns true.
	 */
	public function setUserField($userField) {
		$userField = trim($userField);
		if ( false === empty($userField) ) {
			$this->_userField = $userField;
		}
		return true;
	}
	
	/**
	 * Sets the name of the user_password field to load up the user from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $passwordField The name of the user_password field to set. Will not be set if the value is empty.
	 * @retval boolean Returns true.
	 */
	public function setPasswordField($passwordField) {
		$passwordField = trim($passwordField);
		if ( false === empty($passwordField) ) {
			$this->_passwordField = $passwordField;
		}
		return true;
	}
	
	/**
	 * Sets the name of the user_password_salt field to load up the user from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $passwordSaltField The name of the user_password_salt field to set. Will not be set if the value is empty.
	 * @retval boolean Returns true.
	 */
	public function setPasswordSaltField($passwordSaltField) {
		$passwordSaltField = trim($passwordSaltField);
		if ( false === empty($passwordSaltField) ) {
			$this->_passwordSaltField = $passwordSaltField;
		}
		return true;
	}
	
	/**
	 * Sets the name of the user_id field to load up the user from.
	 * @author vmc <vmc@leftnode.com>
	 * @param $idField The name of the user_id field to set. Will not be set if the value is empty.
	 * @retval boolean Returns true.
	 */
	public function setIdField($idField) {
		$idField = trim($idField);
		if ( false === empty($idField) ) {
			$this->_idField = $idField;
		}
		return true;
	}
	
	/**
	 * Authenticate a use against a database. Assumes the User has been set in 
	 * the method $this->setUser().
	 * @author vmc <vmc@leftnode.com>
	 * @param $validation_hook Optional hook/callback to call after validation to further validate the data.
	 * @throw Artisan_Auth_Exception If the user of the class is not of type Artisan_User.
	 * @throw Artisan_Auth_Exception If the user fails to authenticate because no records are found.
	 * @throw Artisan_Auth_Exception If the user fails to authenticate because more than one record was found.
	 * @throw Artisan_Auth_Exception If the hashed passwords do not match.
	 * @retval boolean True if fully authenticated, false otherwise.
	 */
	public function authenticate($validation_hook = NULL) {
		$this->_checkDb();
		
		$authenticated = false;
		
		// See if a user has been set, if not, throw an exception
		if ( false === $this->_artisanUser instanceof Artisan_User ) {
			throw new Artisan_Auth_Exception(ARTISAN_ERROR, 'Failed to authenticate, the user object has not been set.');
		}
		
		// Get the username and password from the user object
		// Always assume the password is hashed already as it shouldn't be stored
		// unhashed in the Artisan_User class.
		$user_name = $this->_artisanUser->{$this->_userField};
		$user_password = $this->_artisanUser->{$this->_passwordField};
		
		// First, attempt to authenticate on the $_userField to ensure only a single
		// record is found.
		$result_user = $this->_dbConn->select()
			->from($this->_tableName)
			->where($this->_userField . ' = ?', $user_name)
			->query();
		
		// First, ensure at least one row was found.
		if ( 0 === $result_user->numRows() ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'Failed to authenticate, no matching records found.');
		}
		
		// Next, ensure not more than one row was found.
		if ( $result_user->numRows() > 1 ) {
			$row_count = $result_user->numRows();
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'Failed to authenticate, more than one matching record found. ' . $row_count . ' records found.');
		}		
		
		$user_data = $result_user->fetch();

		// Now that we're certain the user only has a single instance, 
		// run the Artisan hashing algorithm on the password and ensure
		// they match.
		if ( false === asfw_exists($this->_passwordField, $user_data) ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, "The password field '" . $this->_passwordField . "' was not found in the user data.");
		}
		
		// If they have a salt from the database, use it.
		$password_salt = asfw_exists_return($this->_passwordSaltField, $user_data);
		
		$user_password_hashed = asfw_compute_hash($user_password, $password_salt);
		$user_data_password_hashed = trim($user_data[$this->_passwordField]);

		$user_id = asfw_exists_return($this->_idField, $user_data);
		
		unset($result_user);
		
		if ( $user_password_hashed !== $user_data_password_hashed ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'Failed to authenticate, the passwords do not match.');
		}
		
		/**
		 * The $validation_hook is a name of a function that takes a single argument
		 * as the array of user data.
		 * @code
		 * function validate_more($user_data) {
		 *     if ( $user_data['user_id'] == 5 ) {
		 *         return true;
		 *     }
		 *     return false;
		 * }
		 * @endcode
		 */
		if ( false === empty($validation_hook) ) {
			$hook = new ReflectionFunction($validation_hook);
			$authenticated = $hook->invoke($user_data);
		} else {
			// No post validation hooks, made it this far, everything is authorized
			// and ready to go.
			$authenticated = true;
		}
		
		if ( true !== $authenticated ) {
			$user_id = 0;
		}
		
		return $user_id;
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
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
		}
		return true;
	}
}