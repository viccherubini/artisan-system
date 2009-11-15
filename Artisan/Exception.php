<?php

/**
 * Much nicer exception and error handling.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Exception extends Exception {
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
			$class = $trace['class'];
			$error_class = $class . $trace['type'];
		}
		
		if ( true === isset($trace['function']) ) {
			$function = $trace['function'];
			$error_class .= $function . '() > ';
		}
		
		$error_file = $this->getFile() . ' +' . $this->getLine();
		$error_code = $this->getMessage() . ' (' . $error_file . ')';
		
		return $error_class . $error_code;
	}
}