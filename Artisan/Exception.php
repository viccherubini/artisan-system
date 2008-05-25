<?php

/**
 * Artisan_Exception is a child of PHP's internal class Exception. As a result, it is *NOT* built
 * by Artisan. Therefore, it does not contain the similar methods of other Artisan classes. 
 * Artisan has it's own Exception class to overcome some of the shortcomings of the built in PHP
 * Exception class.
 */

define('ARTISAN_ERROR_CORE', 100, false);
define('ARTISAN_WARNING', 200, false);
define('ARTISAN_NOTICE', 300, false);

class Artisan_Exception extends Exception {
	/**
	 * The line number the error occured on.
	 */
	private $_line_number;
	
	/**
	 * The file name the error occured in.
	 */
	private $_file_name;
	
	/**
	 * The class name the error occured in.
	 */
	private $_class_name;
	
	/**
	 * The function name the error occured in.
	 */
	private $_function_name;
	
	public function __construct($error_code, $error_message, $class_name = NULL, $function_name = NULL) {
		parent::__construct($error_message, $error_code);
		
		$this->_line_number = intval( parent::getLine() );
		$this->_file_name = basename( parent::getFile() );
		$this->_class_name = $class_name;
		$this->_function_name = $function_name;
	}
	
	/**
	 * Return the name of this class.
	 */
	public function name() { return __CLASS__; }

	/**
	 * Overloaded Exception::toString() method. Stylizes how the error string looks.
	 */
	public function toString() {
		$error_class = NULL;
		if ( false === empty($this->_class_name) ) {
			$error_class = $this->name() . '::';
		}
		
		if ( false === empty($this->_function_name) ) {
			$error_class .= $this->_function_name . '() > ';
		}
		
		$error_file = $this->_file_name . ' +' . $this->_line_number;
		$error_code = stripslashes($this->message) . ' (' . $error_file . ', Code: ' . $this->code . ')';
		
		return $error_class . $error_code;
	}
	
	
	public function __toString() {
		return $this->toString();
	}
	
	/**
	 * Returns the file name the error occurred in.
	 */
	public function getFileName() {
		return $this->_file_name;
	}
	
	/**
	 * Returns the line number the error occurred on.
	 */
	public function getLineNumber() {
		return $this->_line_number;
	}
	
	/**
	 * Returns the class name the error occurred in.
	 */
	public function getClassName() {
		return $this->_class_name;
	}
	
	/**
	 * Returns the function name the error occurred in.
	 */
	public function getFunctionName() {
		return $this->_function_name;
	}
}

?>