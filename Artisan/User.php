<?php

/**
 * @see Artisan_Vo
 */
require_once 'Artisan/Vo.php';

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

	/**
	 * Return's the User's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @retval integer Returns the User's ID.
	 */
	public function getUserId() {
		return $this->_user_id;
	}

	public function __set($name, $value) {
	
	}
	
	public function __get($name) {
		if ( true === $this->_user instanceof Artisan_Vo ) {
		
		}
	}
	
	/**
	 * Return's the User record for inserting or updating.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the record as a Value Object.
	 */
	protected function _makeRecord() {
		$user_data = array(
			'user_id' => $this->getId(),
			'user_name' => $this->getName(),
			'user_password' => $this->getPassword(),
			'user_password_salt' => $this->getPasswordSalt(),
			'user_email_address' => $this->getEmailAddress(),
			'user_firstname' => $this->getFirstname(),
			'user_middlename' => $this->getMiddleName(),
			'user_lastname' => $this->getLastname(),
			'user_status' => $user_name
		);
		
		return new Artisan_VO($user_data);
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