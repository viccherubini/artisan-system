<?php

/**
 * @see Artisan_Library
 */
require_once 'Artisan/Library.php';

/**
 * @see Artisan_Db_Exception
 */
require_once 'Artisan/Db/Exception.php';

/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db {
	///< Holds the database configuration information, must be of type Artisan_Config.
	protected $CONFIG = NULL;

	///< The default adapter.
	const DEFAULT_ADAPTER = 'Artisan_Db_Adapter';

	///< The name for the Mysqli adapter.
	const DB_MYSQLI = 'mysqli';
	
	///< The name for the Postgres adapter.
	const DB_POSTGRES = 'postgres';
	
	///< The name for the Postgresql adapter (alias for the Postgres adapter).
	const DB_POSTGRESQL = 'postgresql';
	
	///< The name for the PDO (PHP Data Objects) adapter.
	const DB_PDO = 'pdo';
	
	///< The name for the Sqlite adapter.
	const DB_SQLITE = 'sqlite';

	/**
	 * Builds a database object based on the adapter specified
	 * in the configuration.
	 * @author vmc <vmc@leftnode.com>
	 * @param $CFG Configuration object of type Artisan_Config passed by reference. Must
	 * have following values:
	 * @code
	 * // Required:
	 * $CFG->server
	 * $CFG->username
	 * $CFG->password
	 * $CFG->database
	 * // Optional
	 * $CFG->port
	 * @endcode
	 * @throw Artisan_Db_Exception If the adapter is not present in the configuration.
	 * @throw Artisan_Db_Exception If one of the required keys above are not present in the configuration.
	 * @retval mixed New database object if successfully created, NULL otherwise.
	 */
	static public function factory(Artisan_Config &$CFG) {
		// Ensure an adapter exists
		if ( false === asfw_exists('adapter', $CFG) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'No adapter present in the configuration.');
		}
		
		// Ensure the following keys exist in the configuration
		$all_keys = array('server', 'username', 'password', 'database');
		if ( false === asfw_exists_all($all_keys, $CFG) ) {
			throw new Artisan_Db_Exception(ARTISAN_WARNING, 'One of the following keys are absent from the configuration: ' . implode(', ', $all_keys) . '.');
		}
		
		// Load in the appropriate file
		$adapter = strtolower(trim($CFG->adapter));
		$adapter = str_replace(' ', '_', ucwords(str_replace('_', ' ', $adapter)));
		$adapter = self::DEFAULT_ADAPTER . '_' . $adapter;
		
		Artisan_Library::load($adapter);
		
		if ( true === class_exists($adapter) ) {
			$db = new $adapter($CFG);
			
			return $db;
		}
		
		return NULL;
	}
}