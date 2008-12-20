<?php

/**
 * This abstract class allows a programmer to build their own User class, with this
 * one containing common values amongst just about any User class.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_User {
	///< The ID of the user.
	protected $_user_id;
	
	///< The username of the user for logging in and authentication.
	protected $_user_name;
	
	///< The password of the user.
	protected $_user_password;
	
	///< The nonce/salt of the password for extra security.
	protected $_user_password_salt;
	
	///< The email address of the user.
	protected $_user_email_address;

	///< The user's first name.
	protected $_user_firstname;
	
	///< The user's middle name, optional.
	protected $_user_middlename;
	
	///< The user's last name.
	protected $_user_lastname;

	///< The status of the user, integer value.
	protected $_user_status;

	/**
	 * Set's the User's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_id The user ID, must be an integer.
	 * @retval boolean Returns true.
	 */
	public function setId($user_id) {
		$user_id = intval($user_id);
		$this->_user_id = ( $user_id > 0 ? $user_id : 0 );
		return true;
	}

	/**
	 * Set's the User Name.
	 * @author vmc <vmc@leftnode.com>
	 * @param $user_name The user name.
	 * @retval boolean Returns true.
	 */
	public function setName($user_name) {
		$this->_user_name = trim($user_name);
		return true;
	}

	/**
	 * Set's the User's Email Address.
	 * @author vmc <vmc@leftnode.com>
	 * @param $email_address The email address of the user.
	 * @todo Ensure the email address is valid.
	 * @retval boolean Returns true.
	 */
	public function setEmailAddress($email_address) {
		// Validate the email address
		$email_address = trim($email_address);
		//if ( true === Artisan_Validate::validateEmailAddress($email_address) ) {
			$this->_user_email_address = $email_address;
		//}
		return true;
	}

	/**
	 * Set's the User's First Name.
	 * @author vmc <vmc@leftnode.com>
	 * @param $firstname The user's first name.
	 * @retval boolean Returns true.
	 */
	public function setFirstname($firstname) {
		$this->_user_firstname = trim($firstname);
		return true;
	}

	/**
	 * Set's the User's Middle Name.
	 * @author vmc <vmc@leftnode.com>
	 * @param $middlename The user's middle name.
	 * @retval boolean Returns true.
	 */
	public function setMiddlename($middlename) {
		$this->_user_middlename = trim($middlename);
		return true;
	}

	/**
	 * Set's the User's Last Name.
	 * @author vmc <vmc@leftnode.com>
	 * @param $firstname The user's last name.
	 * @retval boolean Returns true.
	 */
	public function setLastname($lastname) {
		$this->_user_lastname = trim($lastname);
		return true;
	}

	/**
	 * Set's the User's Password.
	 * @author vmc <vmc@leftnode.com>
	 * @param $firstname The user's first name.
	 * @retval boolean Returns true.
	 */
	public function setPassword($password) {
		$this->_user_password = trim($password);
		return true;
	}
	
	/**
	 * Set's the Password's nonce/salt.
	 * @author vmc <vmc@leftnode.com>
	 * @param $salt The nonce/salt associated with this password.
	 * @retval boolean Returns true.
	 */
	public function setPasswordSalt($salt) {
		$this->_user_password_salt = trim($salt);
		return true;
	}
	
	/**
	 * Set's the User's Status.
	 * @author vmc <vmc@leftnode.com>
	 * @param $status The user's status, must be an integer.
	 * @retval boolean Returns true.
	 */
	public function setStatus($status) {
		$status = intval($status);
		$this->_user_status = $status;
		return true;
	}

	/**
	 * Return's the User's ID.
	 * @author vmc <vmc@leftnode.com>
	 * @retval integer Returns the User's ID.
	 */
	public function getId() {
		return $this->_user_id;
	}

	/**
	 * Return's the User's Name.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the User's Name.
	 */
	public function getName() {
		return $this->_user_name;
	}

	/**
	 * Return's the User's Password.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the User's Password.
	 */
	public function getPassword() {
		return $this->_user_password;
	}

	/**
	 * Return's the User's Email Address.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the User's Email Address.
	 */
	public function getEmailAddress() {
		return $this->_user_email_address;
	}

	/**
	 * Return's the User's First Name.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the User's First Name.
	 */
	public function getFirstname() {
		return $this->_user_firstname;
	}

	/**
	 * Return's the User's Middle Name.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the User's Middle Name.
	 */
	public function getMiddlename() {
		return $this->_user_middlename;
	}

	/**
	 * Return's the User's Last Name.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the User's Last Name.
	 */
	public function getLastname() {
		return $this->_user_lastname;
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
