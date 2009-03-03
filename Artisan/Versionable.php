<?php

class Artisan_Versionable {

	private $_obj;

	private $_dbConn;

	private $_head = 0;
	
	public function __construct(&$obj) {
		// The object passed in must implement Artisan_Interface_Versionable
		// so that it has the appropriate methods.
		if ( true === is_object($obj) ) {
			if ( true === in_array('Artisan_Interface_Versionable', class_implements($obj)) ) {
				$this->_obj = $obj;
			}
		}
	}


	public function setDb(Artisan_Db &$dbConn) {
		_asfw_check_db($dbConn);
		$this->_dbConn = $dbConn;
	}
	
	
	public function write() {
	
	}
}