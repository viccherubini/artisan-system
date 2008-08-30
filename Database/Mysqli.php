<?php

// Load in its own Parameterized Sql classes
Artisan_Library::load('Sql/Mysqli/Select');
Artisan_Library::load('Sql/Mysqli/Insert');

/**
 * The Mysqli class for connecting to a mysql database.
 * @author vmc <vmc@leftnode.com>
 */
class Artisan_Database_Mysqli extends Artisan_Database {
	private $CONN = NULL;
	private $RESULT = NULL;
	private $_query_list = NULL;
	private $_sql_type = NULL;
	private $_is_connected = false;
	private $_transaction_started = false;


	public $select = NULL;
	public $insert = NULL;
	public $update = NULL;

	public function __destruct() {
		if ( true === $this->_is_connected && true === is_object($this->CONN) ) {
			$this->disconnect();
		}
		unset($this->CONFIG);
		
		//$this->select = new Artisan_Sql_Select_Mysqli();
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
	 * Return the number of rows after a SELECT query.
	 * @retval integer The number of rows from the last query.
	 */
	/*
	public function getNumRows() {
		if ( true === is_object($this->RESULT) ) {
			return $this->RESULT->num_rows;
		}

		return 0;
	}
	*/
	
	/**
	 * Return the number of rows affected after a query that alters rows.
	 * @retval integer The number of rows affected from the last INSERT, UPDATE or DELETE clause.
	 */
	/*
	public function getAffectedRows() {
		if ( true === is_object($this->CONN) ) {
			return $this->CONN->affected_rows;
		}

		return 0;
	}
	*/
	/**
	 * Performs a query against the database.
	 * @author vmc <vmc@leftnode.com>
	 * @param $sql The SQL query to execute, either a SQL string or type Artisan_Sql
	 * @throws Artisan_Database_Exception Throws an exception if an error occurs in the SQL.
	 * @return Returns the result object if a valid result.
	 * @todo Implement a query history to create metrics from.
	 */
	/*
	public function query($sql) {
		$query = $sql;
		if ( Artisan_Sql instanceof $sql ) {
			$query = $sql->get();
		}

		$result = $this->CONN->query($query);

		if ( true === is_object($result) ) {
			$this->RESULT = $result;
		} else {
			throw new Artisan_Database_Exception(ARTISAN_WARNING, $this->CONN->error, __CLASS__, __FUNCTION__);
		}

		return $result;
	}
	*/

	/**
	 * Fetch an array row from the database. If this is used in a loop,
	 * such as a while ( $data = $db->fetch() ), the last call will
	 * return a null value, and thus trigger the free() method below
	 * to be called, ensuring the memory is always freed.
	 * @author vmc <vmc@leftnode.com>
	 */
	/*
	public function fetchRow() {
		$data = $this->RESULT->fetch_assoc();
		if ( true === is_null($data) ) {
			$this->free();
		}

		return $data;
	}
	*/
	
	/**
	 * Free the memory from the last SQL statement (generally only
	 * from SELECT or EXPLAIN queries).
	 * @author vmc <vmc@leftnode.com>
	 */
	/*
	public function free() {
		if ( true === is_object($this->RESULT) ) {
			$this->RESULT->free();
		}

		return true;
	}
	*/
	/**
	 * Whether or not a connection to the database exists.
	 */
	public function isConnected() {
		return $this->_is_connected;
	}

	/**
	 * Escape a string with the correct character set.
	 */
	public function escape($string) {
		return $this->CONN->real_escape_string($string);
	}



	public function start() {
		if ( false === $this->_transaction_started ) {
			$this->CONN->autocommit(false);
			$this->_transaction_started = true;
		}
	}


	public function commit() {
		if ( true === $this->_transaction_started ) {
			$this->CONN->commit();
		}
	}

	public function rollback() {
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
