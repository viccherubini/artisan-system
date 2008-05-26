<?php

class Artisan_Template_Database extends Artisan_Template {
	private $_db = NULL;


	private $_config = array();

	public function __construct(Artisan_Config $config = NULL) {
		$this->_config = $config;
		if ( true === is_null($config) ) {
			$this->_config = parent::$_config;
		}
		
		if ( false === Artisan_Library::exists('Database') ) {
			Artisan_Library::load('Database');
		}
		
		// Configuration should specify the database connection
		// information, if not, use the default database already created.
		if ( true === is_null($this->_config) || false === is_object($this->_config) ) {
			// Use the default database connection and configuration created elsewhere
			// This can be unsafe as at this point, you may not be aware of what the 
			// last database created was. It is generally suggested you explicitly 
			// specify the configuration information.
			$this->_db = Artisan_Database_Monitor::get();
		} else {
			// This is the preferred method of creating the 
			$this->_db = Artisan_Database_Monitor::get($this->_config);
			$this->_db->setConfig($this->_config);
		}
		
		
	}

	public function __destruct() {
		
	}

	public function load($tname) {
		
		Artisan_Sql_Monitor::set( new Artisan_Sql_Select() );
		$select = Artisan_Sql_Monitor::get();
		
		
		//$select->from(
		
	}
}

?>
