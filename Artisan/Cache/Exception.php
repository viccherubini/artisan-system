<?php


class Artisan_Database_Exception extends Artisan_Exception {


	public function __construct($error_code, $error_message, $class_name = NULL, $function_name = NULL) {
		$error_message .= ' <strong>IN DATABASE ERROR!</strong>';
		parent::__construct($error_code, $error_message, $class_name, $function_name);
	}
}

?>
