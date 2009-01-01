<?php

/**
 * @see Artisan_Vo
 */
require_once 'Artisan/Vo.php';

/**
 * @see Artisan_User_Exception
 */
require_once 'Artisan/User/Exception.php';

/**
 * This abstract class allows a programmer to build their own User class, with this
 * one containing common values amongst just about any User class.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_User {
	///< The ID of the user.
	protected $_user_id;
	
	protected $_user = NULL;

	///< The status of the user, integer value.
	protected $_user_status;

	public function __construct() {
		// This is always built here rather than in the __set() and __get() methods
		// because the Value Object class is such low overhead, that its not worth using
		// Lazy Initialization.
		$this->_user = new Artisan_Vo();
	}

	/**
	 * Return's the User's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @retval integer Returns the User's ID.
	 */
	public function getUserId() {
		return $this->_user_id;
	}

	/**
	 * Magic method overridden to set a piece of information about a user. The fields
	 * can be set dynamically. This will not allow you to set the user_id value though
	 * to prevent failed queries. Also, if the $name is email_address, it will be validated.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the property to set.
	 * @param $value The value of the property to set.
	 * @retval boolean Returns true.
	 */
	public function __set($name, $value) {
		if ( true === $this->_user instanceof Artisan_Vo ) {
			$name = trim($name);
			if ( $name != 'user_id' ) {
				if ( $name == 'email_address' ) {
					// Validate it
				}
				$this->_user->{$name} = $value;
			}
		}
		return true;
	}
	
	/**
	 * Magic method overridden to get a property of the user.
	 * @author vmc <vmc@leftnode.com>
	 * @param $name The name of the property to get.
	 * @retval string Returns the property's value if the property exists, otherwise returns NULL.
	 */
	public function __get($name) {
		if ( true === $this->_user instanceof Artisan_Vo ) {
			$name = trim($name);
			if ( true === $this->_user->exists($name) ) {
				return $this->_user->{$name};
			}
		}
		return NULL;
	}

	/**
	 * Abstract method to write() the user.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function write();

	/**
	 * Loads the user from a specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The ID to load the user from.
	 * @retval boolean Returns true.
	 */
	abstract protected function _load($user_id);

	/**
	 * Inserts the user into the specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful insertion, false otherwise.
	 */
	abstract protected function _insert();

	/**
	 * Updates the user into the specified source..
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful insertion, false otherwise.
	 */
	abstract protected function _update();
}