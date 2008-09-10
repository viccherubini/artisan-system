<?php


Artisan_Library::load('Database/Monitor');
Artisan_Library::load('Database/Exception');

Artisan_Library::load('Sql/Select');
Artisan_Library::load('Sql/Insert');

/**
 * The abstract Database class from which other database classes are extended.
 * Because this class is abstract and contains many abstract members, it is necessary
 * to extend it to use it. For example, if you want to connect to a MySQL database
 * using mysqli, you would use new Artisan_Database_Mysqli($config) and go from there.
 * @author vmc <vmc@leftnode.com>
 */
abstract class Artisan_Database {

	///< Holds the database configuration information, must be of type Artisan_Config.
	protected $CONFIG = NULL;

	/**
	 * Default constructor.
	 * @author vmc <vmc@leftnode.com>
	 * @param $C is an Artisan_Config configuration instance, optional.
	 * @retval object New database instance, ready for connection.
	 */
	public function __construct(Artisan_Config &$C = NULL) {
		if ( true === is_object($C) ) {
			$this->setConfig($C);
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


	public function setConfig(Artisan_Config &$C) {
		$this->CONFIG = $C;
	}

	public function &getConfig() {
		return $this->CONFIG;
	}

	/**
	 * Connects to the specified database.
	 * @author vmc <vmc@leftnode.com>
	 * @throws Artisan_Database_Exception If the connection fails.
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
	 * Whether or not the database currently has a connection.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the database has a connection, false otherwise.
	 */
	abstract public function isConnected();

	/**
	 * Escapes a string with the database specific function and charset
	 * @author vmc <vmc@leftnode.com>
	 * @retval string The properly escaped string.
	 */
	//abstract public function escape($string);

	/**
	 * Starts a transaction.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the transaction was started and no transaction is currently started, false otherwise.
	 */
	abstract protected function _start();
	
	/**
	 * Rolls back a started transaction.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the transaction began and failed, false otherwise.
	 */
	abstract protected function _rollback();
	
	/**
	 * Commits a transaction, saving the data to the database.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the transaction was successfully commited, false otherwise.
	 */
	abstract protected function _commit();

	/**
	 * Queues a list of Artisan_Sql objects to query against the database. If any of the array elements
	 * are not of type Artisan_Sql, or a query that will not return a value greater than 0 for an 
	 * affected_rows() call is run, _rollback() will automatically be called.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Returns true if the list of transactions were successful, false otherwise.
	 */
	abstract public function queue($query_list);
}

?>
