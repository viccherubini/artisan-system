<?php

require_once 'Artisan/Email/Exception.php';

abstract class Artisan_Adapter_Email {
	protected $_toEmailList = NULL;
	protected $_toNameList = NULL;
	
	protected $_fromEmail = NULL;
	protected $_fromName = NULL;
	
	protected $_subject = NULL;
	
	protected $_contentType = 'utf-8';
	
	protected $_body = NULL;
	
	/// The specified language to use: English (en), Japanese (ja) or UTF-8 (uni)
	protected $_language = 'uni';
	
	public function __construct() {
	
	}
	
	public function addTo($email, $name = NULL) {
		$this->_toEmailList[] = $this->_sanitizeHeader($email);
		$this->_toNameList[] = $this->_sanitizeHeader($name);
		return true;
	}
	
	
	public function setFrom($from, $name = NULL) {
		$this->_fromEmail = $this->_sanitizeHeader($from);
		$this->_fromName = $this->_sanitizeHeader($name);
		return true;
	}
	
	public function setSubject($subject) {
		$this->_subject = $this->_sanitizeHeader($subject);
	}
	
	public function setContentType($ct) {
		$this->_contentType = trim($ct);
	}

	public function setBody($body) {
		$this->_body = $this->_sanitizeEol($body);
	}

	
	protected function _sanitizeHeader($header) {
		$header = trim($header);
		$header = str_replace(array("\n", "\r", "\r\n"), array(NULL, NULL, NULL), $header);
		return $header;
	}
	
	protected function _sanitizeEol($string) {
		$string = str_replace(array("\r", "\r\n"), array("\n", "\n"), $string);
		return $string;
	}
	
	abstract public function send();
	
	private function _check() {
		if ( true === empty($this->_body) ) {
			throw new Artisan_Email_Exception(ARTISAN_WARNING, "The body of the email is empty.");
		}
		
		if ( true === empty($this->_subject) ) {
			throw new Artisan_Email_Exception(ARTISAN_WARNING, "The subject of the email is empty.");
		}
		
		if ( 0 == count($this->_toEmailList) ) {
			throw new Artisan_Email_Exception(ARTISAN_WARNING, "The list of recipients is empty.");
		}
		
		if ( true === empty($this->_fromEmail) ) {
			throw new Artisan_Email_Exception(ARTISAN_WARNING, "The from email address is empty.");
		}
	}
}