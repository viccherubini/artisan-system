<?php

//Artisan_Library::load('Database/Exception');

//Artisan_Library::load('Sql/Select');
//Artisan_Library::load('Sql/Update');
//Artisan_Library::load('Sql/Insert');
//Artisan_Library::load('Sql/Delete');
//Artisan_Library::load('Sql/General');
//Artisan_Library::load('Sql/Replace');

//require_once 'Db/Interface.php';



require_once 'Db/Exception.php';


/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db {
	///< Holds the database configuration information, must be of type Artisan_Config.
	protected $CONFIG = NULL;


	const DB_MYSQLI = 'mysqli';
	const DB_POSTGRES = 'postgres';
	const DB_POSTGRESQL = 'postgresql';
	const DB_PDO = 'pdo';
	const DB_SQLITE = 'sqlite';


	static public function factory(Artisan_Config &$CFG) {
		// Ensure an adapter exists
		if ( false === asfw_exists('adapter', $CFG) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'No adapter present in the configuration');
		}
		
		// See if the adapter type exists
		
		// Load in the appropriate file
		
		
	}

}
