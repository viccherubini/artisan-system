<?php

require_once 'Artisan/Db/Exception.php';

require_once 'Artisan/Functions/Array.php';

/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db {
	///< Holds the database configuration information, must be of type Artisan_Config.
	protected $CONFIG = NULL;

	const DEFAULT_ADAPTER = 'Artisan_Db_Adapter';

	const DB_MYSQLI = 'mysqli';
	const DB_POSTGRES = 'postgres';
	const DB_POSTGRESQL = 'postgresql';
	const DB_PDO = 'pdo';
	const DB_SQLITE = 'sqlite';


	static public function factory(Artisan_Config &$CFG) {
		// Ensure an adapter exists
		if ( false === asfw_exists('adapter', $CFG) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'No adapter present in the configuration.');
		}
		
		// Load in the appropriate file
		$adapter = strtolower(trim($CFG->adapter));
		$adapter = str_replace(' ', '_', ucwords(str_replace('_', ' ', $adapter)));
		
		$file = $adapter;
		$adapter = self::DEFAULT_ADAPTER . '_' . $adapter;
		
		/*
		 try {
			Artisan_Library::load($file, 'Db');
		} catch ( Artisan_Exception $e ) {
			throw $e;
		}
		*/
	}
}