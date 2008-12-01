<?php

// Load in its own Parameterized Sql classes
//Artisan_Library::load('Sql/Mysqli/Select');
//Artisan_Library::load('Sql/Mysqli/Update');
//Artisan_Library::load('Sql/Mysqli/Insert');
//Artisan_Library::load('Sql/Mysqli/Delete');
//Artisan_Library::load('Sql/Mysqli/General');
//Artisan_Library::load('Sql/Mysqli/Replace');

require_once 'Artisan/Database.php';

/**
 * The Mysqli class for connecting to a mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Db_Mysqli extends Artisan_Db {
	///< The connection to the database (Mysqli Object)
	private $CONN = NULL;

	///< An instance of a mysqli_result class to build parameterized query.
	//private $STATEMENT = NULL;

	///< The instance of the Artisan_Sql_Select_Mysqli class for executing queries.
	public $select = NULL;
	
	///< The instance of the Artisan_Sql_Insert_Mysqli class for executing queries.
	public $insert = NULL;
	
	///< The instance of the Artisan_Sql_Update_Mysqli class for executing queries.
	public $update = NULL;

	///< The instance of the Artisan_Sql_Delete_Mysqli class for executing queries.
	public $delete = NULL;

	///< The instance of the Artisan_Sql_General_Mysqli class for executing queries.
	public $general = NULL;
	
	///< The instance of the Artisan_Sql_Replace_Mysqli class for executing queries.
	public $replace = NULL;
	
	/**
	 * Destructor, disconnects from the database if currently connected.
	 * @author vmc <vmc@leftnode.com>
	 * @retval NULL Destroys the object.
	 */
	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->CONN) ) {
			$this->disconnect();
			$this->_is_connected = false;
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
		$this->update = new Artisan_Sql_Update_Mysqli($this->CONN);
		$this->insert = new Artisan_Sql_Insert_Mysqli($this->CONN);
		$this->delete = new Artisan_Sql_Delete_Mysqli($this->CONN);
		$this->replace = new Artisan_Sql_Replace_Mysqli($this->CONN);
		$this->general = new Artisan_Sql_General_Mysqli($this->CONN);
		
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

	/**
	 * Prepare a query for execution and then execute it.
	 * @author vmc <vmc@leftnode.com>
	 * @param $sql The SQL to prepare to execute.
	 * @throw Artisan_Database_Exception If the statement is currently fetching all of its data, it can not be restarted.
	 * @throw Artisan_Database_Exception If the query is empty.
	 */
	public function prepareExecute($sql) {
		if ( true === $this->STATEMENT instanceof mysqli_result ) {
			throw new Artisan_Database_Exception(ARTISAN_WARNING, 
				'A statement has not finished fetching all of its data. Please close the current statement.', __CLASS__, __FUNCTION__);
		}

		$sql = trim($sql);
		if ( true === empty($sql) ) {
			throw new Artisan_Database_Exception(ARTISAN_WARNING, 'The SQL statement is empty.', __CLASS__, __FUNCTION__);
		}
	
		$this->STATEMENT = $this->CONN->prepare($sql);
		
		return $this;
	}
	

	/**
	 * Start transtional queries.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Finish implementing this.
	 * @retval boolean Returns true.
	 */
	protected function _start() {
		// Turn autocommit off
		$this->CONN->autocommit(false);
		$this->_transaction_started = true;
		
		return true;
	}

	/**
	 * Rolls back any failed transitional queries.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Finish implementing this, test the rollback and throw an exception.
	 * @retval boolean Returns true.
	 */
	protected function _rollback() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->rollback();
		}
		
		return true;
	}

	/**
	 * Commit a series of SQL transactions.
	 * @author vmc <vmc@leftnode.com>
	 * @todo Finish implementing this, test the commit and throw an exception.
	 * @retval boolean Returns true;
	 */
	protected function _commit() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->commit();
		}
		
		return true;
	}

	/**
	 * Support for transactional queries. If any of the queries fail, all of them fail,
	 * and the database is returned to its original state.
	 * @author vmc <vmc@leftnode.com>
	 * @param $query_list A list of queries to queue and execute. Can be an array of Artisan_Sql objects or an array of string queries.
	 * @retval boolean Returns true if successfully execute, false otherwise.
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
				//$success = $this->query($query_list[$i]);

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
