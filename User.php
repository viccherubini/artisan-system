<?php

abstract class Artisan_User {
	protected $_user_id;
	protected $_user_name;
	protected $_user_password;
	protected $_user_password_salt;
	protected $_user_email_address;

	protected $_user_firstname;
	protected $_user_middlename;
	protected $_user_lastname;

	protected $_user_status;

	public function setId($user_id) {
		$user_id = intval($user_id);
		$this->_user_id = ( $user_id > 0 ? $user_id : 0 );
	}

	public function setName($user_name) {
		$this->_user_name = trim($user_name);
	}

	public function setEmailAddress($email_address) {
		// Validate the email address
		$email_address = trim($email_address);
		//if ( true === Artisan_Validate::validateEmailAddress($email_address) ) {
			$this->_user_email_address = $email_address;
		//}
	}

	public function setFirstname($firstname) {
		$this->_user_firstname = trim($firstname);
	}

	public function setMiddlename($middlename) {
		$this->_user_middlename = trim($middlename);
	}

	public function setLastname($lastname) {
		$this->_user_lastname = trim($lastname);
	}

	public function setPassword($password) {
		$this->_user_password = trim($password);
	}
	
	public function setPasswordSalt($salt) {
		$this->_user_password_salt = trim($salt);
	}
	
	public function setStatus($status) {
		$status = intval($status);
		$this->_user_status = $status;
	}

	public function getId() {
		return $this->_user_id;
	}

	public function getName() {
		return $this->_user_name;
	}

	public function getPassword() {
		return $this->_user_password;
	}

	public function getEmailAddress() {
		return $this->_user_email_address;
	}

	public function getFirstname() {
		return $this->_user_firstname;
	}

	public function getMiddlename() {
		return $this->_user_middlename;
	}

	public function getLastname() {
		return $this->_user_lastname;
	}

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

	abstract public function write();
	abstract protected function _load($user_id);
	abstract protected function _insert();
	abstract protected function _update();
}
