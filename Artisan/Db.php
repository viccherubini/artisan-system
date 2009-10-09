<?php

require_once 'Artisan/Function/Database.php';

require_once 'Artisan/Db/Iterator.php';

/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it. For example, if you want to connect to a MySQL database
 * using mysqli, you would use new Artisan_Database_Mysqli($config) and go from there.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Db {
	///< Holds the database configuration information, must be of type Artisan_Config.
	protected $CONFIG = NULL;

	///< Whether or not a current connection exists.
	protected $_is_connected = false;
	
	///< Whether or not a transaction has been started.
	private $_transaction_started = false;
	
	///< The instance of the Artisan_Sql_Select_* class for executing SELECT queries.
	protected $_select = NULL;
	
	///< The instance of the Artisan_Sql_Update_* class for executing UPDATE queries.
	protected $_update = NULL;
	
	///< The instance of the Artisan_Sql_Insert_* class for executing INSERT/UPDATE queries.
	protected $_insert = NULL;
	
	///< The instance of the Artisan_Sql_Delete_* class for executing DELETE queries.
	protected $_delete = NULL;
	
	protected $_queryList = array();
	
	protected $_debug = false;
	
	/**
	 * Default constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C An Artisan_Config configuration instance, optional.
	 * @retval object New database instance, ready for connection.
	 */
	public function __construct(Artisan_Config $C = NULL) {
		if ( true === is_object($C) ) {
			$this->setConfig($C);
		}
		
		if ( true === $this->CONFIG->exists('debug') ) {
			$this->_debug = $this->CONFIG->debug;
		}
	}

	/**
	 * Default destructor.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Destroys configuration and returns true.
	 */
	public function __destruct() {
		unset($this->CONFIG);
	}

	/**
	 * Returns the name of this class.
	 * @author vmc <vmc@leftnode.com>
	 * @retval string Returns the name of the class.
	 */
	public function name() {
		return __CLASS__;
	}

	/**
	 * Sets the configuration if not set through the constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C A configuration object.
	 * @retval boolean Returns true.
	 */
	public function setConfig(Artisan_Config $C) {
		$this->CONFIG = $C;
		return true;
	}

	/**
	 * Returns the configuration object.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Object Returns the configuration object.
	 */
	public function getConfig() {
		return $this->CONFIG;
	}

	public function getQueryList() {
		return $this->_queryList;
	}
	
	/**
	 * Whether or not the database currently has a connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the database has a connection, false otherwise.
	 */
	public function isConnected() {
		return $this->_is_connected;
	}
	
	/**
	 * Alias for disconnect();
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true upon successful disconnection, false otherwise.
	 */
	public function close() {
		return $this->disconnect();
	}
	
	/**
	 * Connects to the specified database.
	 * @author vmc <vmc@leftnode.com>
	 * @throw Artisan_Database_Exception If the connection fails.
	 * @retval object New connection to the database
	 */
	abstract public function connect();

	/**
	 * Disconnects from the specified database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true upon successful disconnection, false otherwise.
	 */
	abstract public function disconnect();

	/**
	 * Executes a query against the database server.
	 * @author vmc <vmc@leftnode.com>
	 * @param $sql The query to execute.
	 * @retval mixed Returns a result object if the query returns data, boolean true/false otherwise.
	 */
	abstract public function query($sql);
	
	/**
	 * Creates a new SELECT object to fetch data from the database. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Select_* object.
	 */
	abstract public function select();
	
	/**
	 * Creates a new INSERT object to add data to the database. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Insert_* object.
	 */
	abstract public function insert();
	
	/**
	 * Creates a new REPLACE object. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Insert_* object with the REPLACE parameter set.
	 */
	abstract public function replace();
	
	/**
	 * Creates a new UPDATE object. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval object Returns new Artisan_Db_Sql_Update_* object.
	 */
	abstract public function update();
	
	/**
	 * Creates a new DELETE object. Uses Lazy Initialization.
	 * @author vmc <vmc@leftnode.com>
	 * @retval Returns a new Artisan_Db_Sql_Delete_* object.
	 */
	abstract public function delete();
	
	/**
	 * Starts a transaction if the database or table type supports transactions.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true.
	 */
	abstract public function start();
	
	/**
	 * Commits the transaction queries.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on success, false otherwise.
	 */
	abstract public function commit();
	
	/**
	 * Rollbacks the transaction queries if any of them fail.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true on success, false otherwise.
	 */
	abstract public function rollback();
	
	/**
	 * Returns the last INSERT ID from the last INSERT query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int The INSERT ID.
	 */
	abstract public function insertId();
	
	/**
	 * Returns the number of affected rows from the last UPDATE/INSERT/REPLACE query.
	 * @author vmc <vmc@leftnode.com>
	 * @retval int The number of affected rows.
	 */
	abstract public function affectedRows();
	
	/**
	 * Escapes a value to make it safe for insertion into a database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $value The value to escape.
	 * @retval string The escaped value.
	 */
	abstract public function escape($value);
}

/**
 * Checks to see if a variable has an active connection to a database.
 * Because this method should not be used publically, it is prefixed with an underscore.
 * @author vmc <vmc@leftnode.com>
 * @param $dbConn An Artisan_Db object.
 * @throw Artisan_Db_Exception If the database does not have an active connection.
 * @retval boolean Returns true.
 */
function _asfw_check_db(Artisan_Db $dbConn) {
	if ( false === $dbConn->isConnected() ) {
		throw new Artisan_Db_Exception(ARTISAN_WARNING, 'The database does not have an active connection.');
	}
	return true;
}