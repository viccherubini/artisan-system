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
class Artisan_User {
	///< The ID of the user.
	protected $_user_id;
	
	///< The use Value Object for storing dynamic user data.
	protected $_user = array();
	
	///< The status of the user, integer value.
	protected $_user_status;

	/**
	 * Default constructor, must be called in children classes!
	 * @author vmc <vmc@leftnode.com>
	 * @retval object New Artisan_User object.
	 */
	public function __construct() {
		// This is always built here rather than in the __set() and __get() methods
		// because the Value Object class is such low overhead, that its not worth using
		// Lazy Initialization.
		//$this->_user = new Artisan_Vo();
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
	 * Sets all of the user values from an array.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user The user array to set.
	 * @retval boolean Returns true if $user is an array and the values are set, false otherwise.
	 */
	public function setFromArray($user) {
		if ( false === is_array($user) ) {
			return false;
		}
		
		foreach ( $user as $k => $v ) {
			$this->__set($k, $v);
		}
		return true;
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
		$name = trim($name);
		if ( $name != 'user_id' ) {
			if ( $name == 'email_address' ) {
				// Validate it
			}
			$this->_user[$name] = $value;
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
		$name = trim($name);
		if ( true === asfw_exists($name, $this->_user) ) {
			return $this->_user[$name];
		}
		return NULL;
	}

	/**
	 * Abstract method to write() the user.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	public function write() {
		return true;
	}

	/**
	 * Loads the user from a specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The ID to load the user from.
	 * @retval boolean Returns true.
	 */
	protected function _load($user_id) {
		return true;
	}

	/**
	 * Inserts the user into the specified source.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful insertion, false otherwise.
	 */
	protected function _insert() {
		return true;
	}

	/**
	 * Updates the user into the specified source..
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on successful insertion, false otherwise.
	 */
	protected function _update() {
		return true;
	}
}