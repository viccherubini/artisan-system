<?php

/**
 * @see Artisan_Auth
 */
require_once 'Artisan/Auth.php';

/**
 * @see Artisan_Auth_Exception
 */
require_once 'Artisan/Auth/Exception.php';

/**
 * This class authenticates a user against a database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Auth_Db extends Artisan_Auth {
	///< The database connection instance.
	private $DB = NULL;
	
	///< The name of the table that holds user data, this is also defined in Artisan/User/Database.php!
	const TABLE_USER = 'artisan_user';
	
	/**
	 * Default constructor to authenticate someone against a database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object New Artisan_Auth_Database instance.
	 */
	public function __construct(Artisan_Db &$DB) {
		// We can only assume the database has a current connection
		// as we don't want to attempt to connect.
		$this->DB = &$DB;
	}
	
	/**
	 * Authenticate a use against a database. Assumes the User has been set in 
	 * the method $this->setUser().
	 * @author vmc <vmc@leftnode.com>
	 * @param $validation_hook Optional hook/callback to call after validation to further validate the data.
	 * @retval boolean True if fully authenticated, false otherwise.
	 */
	public function authenticate($validation_hook = NULL) {
		$authenticated = false;
		
		// See if a user has been set, if not, throw an exception
		if ( false === $this->USER instanceof Artisan_User ) {
			throw new Artisan_Auth_Exception(ARTISAN_ERROR, 'Failed to authenticate, the user object has not been set.', __CLASS__, __FUNCTION__);
		}
		
		// Get the username and password from the user object
		// Always assume the password is hashed already as it shouldn't be stored
		// unhashed in the Artisan_User class.
		$user_name = $this->USER->getName();
		$user_password = $this->USER->getPassword();
		
		$result_user = $this->DB->select()
			->from(self::TABLE_USER, asfw_create_table_alias(self::TABLE_USER))
			->where('user_name = ?', $user_name)
			->where('user_password = ?', $user_password)
			->query();
			
		// First, ensure at least one row was found.
		if ( 0 === $result_user->numRows() ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'Failed to authenticate, no matching records found.', __CLASS__, __FUNCTION__);
		}
		
		// Next, ensure not more than one row was found.
		if ( $result_user->numRows() > 1 ) {
			throw new Artisan_Auth_Exception(ARTISAN_WARNING, 'Failed to authenticate, more than one matching record found. ' . $row_count . ' records found.', __CLASS__, __FUNCTION__);
		}
		
		$user_data = $result_user->fetch();
		
		unset($result_user);
		
		// Ok, we're certain only one matching record was found.
		// See if there is a hook to call on the data returned
		if ( false === empty($validation_hook) ) {
			$hook = new ReflectionFunction($validation_hook);
			$authenticated = $hook->invoke($user_data);
		} else {
			// No post validation hooks, made it this far, everything is authorized
			// and ready to go.
			$authenticated = true;
		}
		
		return $authenticated;
	}
}
