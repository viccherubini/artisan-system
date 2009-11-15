<?php

/**
 * Much nicer exception and error handling.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Exception extends Exception {
	private $_class = NULL;
	
	private $_function = NULL;
	
	/**
	 * Default constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $error_message The specific error message.
	 * @retval Object Returns new Artisan_Exception object.
	 */
	public function __construct($error_message) {
		parent::__construct($error_message);
	}
	
	/**
	 * Overloaded Exception::__toString() to echo out the correct string.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the string with exception data.
	 */
	public function __toString() {
		$trace = parent::getTrace();
		$trace = current($trace);

		$error_class = NULL;
		if ( true === isset($trace['class']) ) {
			$this->_class = $trace['class'];
			$error_class = $this->_class . $trace['type'];
		}
		
		if ( true === isset($trace['function']) ) {
			$this->_function = $trace['function'];
			$error_class .= $this->_function . '() > ';
		}
		
		$error_file = parent::getFile() . ' +' . parent::getLine();
		$error_code = $this->message . ' (' . $error_file . ')';
		
		return $error_class . $error_code;
	}
}