<?php

// Load in its own Parameterized Sql classes
Artisan_Library::load('Sql/Mysqli/Select');
Artisan_Library::load('Sql/Mysqli/Insert');

/**
 * The Mysqli class for connecting to a mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Database_Mysqli extends Artisan_Database {
	///< The connection to the database (Mysqli Object)
	private $CONN = NULL;

	///< Whether or not the instance of this class is currently connected to the database.
	private $_is_connected = false;

	///< Whether or not a transaction has been started.
	private $_transaction_started = false;

	///< The instance of the Artisan_Sql_Select_Mysqli class for executing queries.
	public $select = NULL;
	
	///< The instance of the Artisan_Sql_Insert_Mysqli class for executing queries.
	public $insert = NULL;
	
	///< The instance of the Artisan_Sql_Update_Mysqli class for executing queries.
	public $update = NULL;


	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->CONN) ) {
			$this->disconnect();
		}
		unset($this->CONFIG);
	}


	/**
	 * Connect to the database.
	 * @throw Artisan_Database_Exception Throws a new exception if the database connection can not be made.
	 * @retval Object New database connection
	 */
	public function connect() {
		$server = $this->CONFIG->server;
		$username = $this->CONFIG->username;
		$password = $this->CONFIG->password;
		$database = $this->CONFIG->database;

		$port = 3306;
		if ( true === @isset($this->CONFIG->port) ) {
			if ( intval($this->CONFIG->port) > 0 ) {
				$port = intval($this->CONFIG->port);
			}
		}

		// Although generally against supressing errors, the @ is
		// to supress a misconnection error
		// to allow the framework to handle it gracefully
		$this->CONN = @new mysqli($server, $username, $password, $database, $port);

		if ( 0 != mysqli_connect_errno() || false === $this->CONN ) {
			$this->_is_connected = false;
			throw new Artisan_Database_Exception(ARTISAN_WARNING, mysqli_connect_error(), __CLASS__, __FUNCTION__);
		}

		$this->_is_connected = true;
		
		// Set the connection for the parameterized SQL
		$this->select = new Artisan_Sql_Select_Mysqli($this->CONN);
		$this->insert = new Artisan_Sql_Insert_Mysqli($this->CONN);
		
		return $this->CONN;
	}

	/**
	 * Disconnect from the database if already connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean Always returns true.
	 */
	public function disconnect() {
		if ( true === $this->_is_connected ) {
			$this->CONN->close();
			$this->CONN = NULL;
			$this->_is_connected = false;
		}

		return true;
	}

	/**
	 * Whether or not a connection to the database exists.
	 * @author vmc <vmc@leftnode.com>
	 * @retval boolean True if connected, false otherwise.
	 */
	public function isConnected() {
		return $this->_is_connected;
	}


	public function startTransaction() {
		if ( false === $this->_transaction_started ) {
			$this->CONN->autocommit(false);
			$this->_transaction_started = true;
		}
	}


	public function commitTransaction() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->commit();
		}
	}

	public function rollbackTransaction() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->rollback();
		}
	}












	/**
	 * Start transtional queries.
	 */
	protected function _start() {
		// Turn autocommit off
		$this->CONN->autocommit(false);
		$this->_transaction_started = true;
	}

	/**
	 * Rolls back any failed transitional queries.
	 */
	protected function _rollback() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->rollback();
		}
	}

	/**
	 * Commit a series of SQL transactions.
	 */
	protected function _commit() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->commit();
		}
	}

	/**
	 * Support for transactional queries. $query_list should be an array
	 * of Artisan_Sql objects. If any of the queries fail, all of them fail,
	 * and the database is returned to its original state.
	 */
	public function queue($query_list) {
		$this->_start();

		if ( false === is_array($query_list) || count($query_list) < 1 ) {
			$this->_transaction_started = false;
			return false;
		}

		$error = false;
		$len = count($query_list);
		for ( $i=0; $i<$len; $i++ ) {
			if ( true === $query_list[$i] instanceof Artisan_Sql ) {
				$success = $this->query($query_list[$i]);

				if ( false === $success ) {
					$error = true;
					break;
				}
			}
		}

		if ( true === $error ) {
			$this->_rollback();
		} else {
			$this->_commit();
		}

		return $error;
	}
}

?>
