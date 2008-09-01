<?php

class Artisan_Template_Database extends Artisan_Template {
	private $DB = NULL;

	/**
	 * Constructor for the Artisan_Template class to get the templates from the database.
	 * @author vmc <vmc@leftnode.com>
	 * @throws Artisan_Database_Exception If the database object passed into the class can not be connected to.
	 * @retval Object The new Artisan_Template_Database object.
	 */
	public function __construct(Artisan_Database &$db) {
		if ( false === $db->isConnected() ) {
			// Try to connect to the database
			try {
				$db->connect();
			} catch ( Artisan_Database_Exception $e ) {
				throw new Artisan_Template_Exception(ARTISAN_ERROR_CORE, $e->getMessage(), __CLASS__, __FUNCTION__);
			}
		}
		
		$this->DB = &$db;
	}

	public function __destruct() {
		
	}

	public function load($tname) {
		//$this->DB->select->from('my_table')->build();
		//echo $this->DB->select;
	}
	
	public function setTheme($theme) {

	}
}

?>
