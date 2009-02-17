<?php


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
		$this->_toEmailList[] = $email;
		$this->_toNameList[] = $name;
		return true;
	}
	
	
	public function setFrom($from, $name = NULL) {
		$this->_fromEmail = $from;
		$this->_fromName = $name;
		return true;
	}
	
	public function setSubject($subject) {
		$this->_subject = trim($subject);
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
}