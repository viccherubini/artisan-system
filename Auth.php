<?php

Artisan_Library::load('Auth/Exception');

/**
 * Authentication takes a username and password and authenticates it against a specified system. 
 * To remain true to the mantra of do one thing and do it well, Authentication does not initialize
 * a Session after successful authentication. Rather, the class returns true or false, and it is up
 * to the calling script to initialize/destroy a session.
 * Authentication uses a pattern called a Factory. Because there are many different authentication
 * schemes (htpasswd, ldap, database, xml file, etc.), it doesn't make sense to include a method for
 * authenticating against each because a lot of code would be rewritten. Instead, children classes
 * extend this class. Those children classes contain specific implementations of how to authorize
 * against a scheme, which returns the result to this class. This class then returns the result back
 * to the programmer, who determines what to do with it.
 * @author vmc <vmc@leftnode.com>
*/
abstract class Artisan_Auth {
	///< The user is anonymous.
	const STATUS_ANONYMOUS = 2;
	
	///< The user is invalid.
	const STATUS_INVALID = 4;
	
	///< The user is valid.
	const STATUS_VALID = 8;
	
	///< The authentication has ended.
	const STATUS_END = 16;
	
	///< The configuration object.
	protected $CONFIG = NULL;
	
	///< The user object to authenticate against.
	protected $USER = NULL;


	/**
	 * Default constructor, this class is abstract.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns new Artisan_Auth instance.
	 */
	public function __construct() { }
	
	/**
	 * Destructor, destroys the object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Returns nothing.
	 */
	public function __destruct() {
		unset($this->CONFIG);
		unset($this->USER);
	}
	
	/**
	 * Sets the configuration if not set in the constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function setConfig(Artisan_Config &$C) {
		$this->CONFIG = $C;
		return true;
	}
	
	/**
	 * Sets the user if not set in the constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function setUser(Artisan_User &$U) {
		$this->USER = $U;
		return true;
	}
	
	/**
	 * Gets the configuration.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the configuration object.
	 */
	public function &getConfig() {
		return $this->CONFIG;
	}
	
	/**
	 * Gets the user.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the user object.
	 */
	public function &getUser() {
		return $this->USER;
	}
	
	/**
	 * Performs the authentication against the specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @param $validation_hook Optional hook to call after validation to further validate the data.
	 * @retval boolean True if able to be authenticated, false otherwise.
	 */
	abstract public function authenticate($validation_hook = NULL);
	
}
