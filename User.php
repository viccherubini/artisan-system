<?php

abstract class Artisan_User {
	protected $_user_id;
	protected $_user_name;
	protected $_user_password;
	protected $_user_email_address;

	protected $_user_firstname;
	protected $_user_middlename;
	protected $_user_lastname;



	public function setUserId($user_id) {
		$user_id = intval($user_id);
		$this->_user_id = ( $user_id > 0 ? $user_id : 0 );
	}

	public function setUserName($user_name) {
		$this->_user_name = trim($user_name);
	}

	public function setUserPassword($user_password) {
		$this->_user_password = trim($user_password);
	}

	public function setUserEmailAddress($email_address) {
		// First, validate the email address
		$email_address = trim($email_address);
		if ( true === Artisan_Validate::validateEmailAddress($email_address) ) {
			$this->_user_email_address = $email_address;
		}
	}

	public function setUserFirstname($firstname) {
		$this->_user_firstname = trim($firstname);
	}

	public function setUserMiddlename($middlename) {
		$this->_user_middlename = trim($middlename);
	}

	public function setUserLastname($lastname) {
		$this->_user_lastname = trim($lastname);
	}


	public function getUserId() {
		return $this->_user_id;
	}

	public function getUserName() {
		return $this->_user_name;
	}

	public function getUserPassword() {
		return $this->_user_password;
	}

	public function getUserEmailAddress() {
		return $this->_user_email_address;
	}

	public function getUserFirstname() {
		return $this->_user_firstname;
	}

	public function getUserMiddlename() {
		return $this->_user_middlename;
	}

	public function getUserLastname() {
		return $this->_user_lastname;
	}

	abstract public function write();
	abstract protected function _load($user_id);
	abstract protected function _insert();
	abstract protected function _update();
	abstract protected function _makeRecord();
}

?>