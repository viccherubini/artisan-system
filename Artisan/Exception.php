<?php

require_once 'Artisan/Functions/Array.php';

define('ARTISAN_ERROR', 100, false);
define('ARTISAN_WARNING', 200, false);
define('ARTISAN_NOTICE', 300, false);

/**
 * Artisan_Exception is a child of PHP's internal class Exception. As a result, it is *NOT* built
 * by Artisan. Therefore, it does not contain the similar methods of other Artisan classes. 
 * Artisan has it's own Exception class to overcome some of the shortcomings of the built in PHP
 * Exception class. Allows for prettier
 * exception formatting.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Exception extends Exception {
	///< The line number that the exception was thrown from.
	private $_line_number;

	///< The file name the error occured in.
	private $_file_name;
	
	/**
	 * Default constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $error_code A custom numeric error code defined above.
	 * @param $error_message The specific error message.
	 * @retval Object Returns new Artisan_Exception object.
	 */
	public function __construct($error_code, $error_message) {
		parent::__construct($error_message, $error_code);
		
		$this->_line_number = parent::getLine();
		$this->_file_name = parent::getFile();
	}
	
	/**
	 * Overloaded Exception::toString() method. Stylizes how the error string looks.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the string with exception data.
	 */
	public function toString() {
		$trace = parent::getTrace();
		$trace = current($trace);

		$error_class = NULL;
		if ( true === asfw_exists('class', $trace) ) {
			$this->_class_name = $trace['class'];
			$error_class = $this->_class_name . $trace['type'];
		}
		
		if ( true === asfw_exists('function', $trace) ) {
			$this->_function_name = $trace['function'];
			$error_class .= $this->_function_name . '() > ';
		}
		
		$error_file = $this->_file_name . ' +' . $this->_line_number;
		$error_code = stripslashes($this->message) . ' (' . $error_file . ', Code: ' . $this->code . ')';
		
		return $error_class . $error_code;
	}
	
	/**
	 * Overloaded Exception::__toString() to echo out the correct string
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the string with exception data.
	 */
	public function __toString() {
		return $this->toString();
	}
	
	/**
	 * Returns the file name the error occurred in.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The name of the file the error occurred in.
	 */
	public function getFileName() {
		return $this->_file_name;
	}
	
	/**
	 * Returns the line number the error occurred on.
	 * @author vmc <vmc@leftnode.com>
	 * @retval integer The line number the error occured on.
	 */
	public function getLineNumber() {
		return $this->_line_number;
	}
	
	/**
	 * Returns the class name the error occurred in.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The name of the class the error occurred in.
	 */
	public function getClassName() {
		return $this->_class_name;
	}
	
	/**
	 * Returns the method name the error occurred in.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The name of the method the error occurred in.
	 */
	public function getFunctionName() {
		return $this->_function_name;
	}
}